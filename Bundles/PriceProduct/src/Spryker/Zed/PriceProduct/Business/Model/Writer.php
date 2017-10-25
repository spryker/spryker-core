<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Zed\PriceProduct\Business\Exception\ProductPriceChangeException;
use Spryker\Zed\PriceProduct\Business\Exception\UndefinedPriceTypeException;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class Writer implements WriterInterface
{
    use DatabaseTransactionHandlerTrait;

    const TOUCH_PRODUCT = 'product';
    const ENTITY_NOT_FOUND = 'entity not found';

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $priceConfig;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    protected $priceTypeReader;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface $reader
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceConfig
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface $productFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceTypeReader
     */
    public function __construct(
        PriceProductQueryContainerInterface $queryContainer,
        ReaderInterface $reader,
        PriceProductToTouchInterface $touchFacade,
        PriceProductConfig $priceConfig,
        PriceProductToProductInterface $productFacade,
        PriceProductTypeReaderInterface $priceTypeReader
    ) {
        $this->queryContainer = $queryContainer;
        $this->reader = $reader;
        $this->touchFacade = $touchFacade;
        $this->priceConfig = $priceConfig;
        $this->productFacade = $productFacade;
        $this->priceTypeReader = $priceTypeReader;
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function createPriceType($name)
    {
        $priceTypeEntity = $this->queryContainer->queryPriceType($name)->findOneOrCreate();
        $priceTypeEntity
            ->setName($name)
            ->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH)
            ->save();

        return $priceTypeEntity->getIdPriceType();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @throws \Spryker\Zed\PriceProduct\Business\Exception\ProductPriceChangeException
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct
     */
    public function createPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireMoneyValue();

        $priceProductTransfer = $this->setPriceType($priceProductTransfer);
        if (!$this->isPriceTypeExistingForProductAbstract($priceProductTransfer)
            && !$this->isPriceTypeExistingForProductConcrete($priceProductTransfer)
        ) {
            $this->loadProductAbstractIdForPriceProductTransfer($priceProductTransfer);
            $this->loadProductConcreteIdForPriceProductTransfer($priceProductTransfer);

            $entity = new SpyPriceProduct();
            $newPrice = $this->savePriceProductEntity($priceProductTransfer, $entity);

            if ($priceProductTransfer->getIdProduct()) {
                $this->insertTouchRecord(self::TOUCH_PRODUCT, $priceProductTransfer->getIdProduct());
            }

            return $newPrice;
        }
        throw new ProductPriceChangeException('This couple product price type is already set');
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @throws \Spryker\Zed\PriceProduct\Business\Exception\ProductPriceChangeException
     *
     * @return void
     */
    public function setPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireMoneyValue();

        $priceProductTransfer = $this->setPriceType($priceProductTransfer);

        if ($this->isPriceTypeExistingForProductConcrete($priceProductTransfer)
            || $this->isPriceTypeExistingForProductAbstract($priceProductTransfer)
        ) {
            $this->loadProductAbstractIdForPriceProductTransfer($priceProductTransfer);
            $this->loadProductConcreteIdForPriceProductTransfer($priceProductTransfer);

            $priceProductEntity = $this->getPriceProductById($priceProductTransfer->getIdPriceProduct());
            $this->savePriceProductEntity($priceProductTransfer, $priceProductEntity);

            if ($priceProductTransfer->getIdProduct()) {
                $this->insertTouchRecord(self::TOUCH_PRODUCT, $priceProductTransfer->getIdProduct());
            }
        } else {
            throw new ProductPriceChangeException('There is no price assigned for selected product!');
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function loadProductAbstractIdForPriceProductTransfer(PriceProductTransfer $priceProductTransfer)
    {
        if ($priceProductTransfer->getIdProductAbstract() !== null) {
            return;
        }

        $priceProductTransfer->setIdProductAbstract(
            $this->productFacade->findProductAbstractIdBySku($priceProductTransfer->getSkuProductAbstract())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function loadProductConcreteIdForPriceProductTransfer(PriceProductTransfer $priceProductTransfer)
    {
        if ($priceProductTransfer->getIdProduct() === null &&
            $this->productFacade->hasProductConcrete($priceProductTransfer->getSkuProduct())
        ) {
            $priceProductTransfer->setIdProduct(
                $this->productFacade->getProductConcreteIdBySku($priceProductTransfer->getSkuProduct())
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct
     */
    protected function savePriceProductEntity(PriceProductTransfer $priceProductTransfer, SpyPriceProduct $priceProductEntity)
    {
        $priceType = $this->reader->getPriceTypeByName($priceProductTransfer->getPriceTypeName());
        $priceProductEntity->setPriceType($priceType);

        if ($priceProductTransfer->getIdProduct()) {
            $priceProductEntity->setFkProduct($priceProductTransfer->getIdProduct());
        } else {
            $priceProductEntity->setFkProductAbstract($priceProductTransfer->getIdProductAbstract());
        }

        $priceProductEntity->save();

        $this->persistPriceProductStore($priceProductTransfer);

        return $priceProductEntity;
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return void
     */
    protected function insertTouchRecord($itemType, $itemId)
    {
        $this->touchFacade->touchActive($itemType, $itemId);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function setPriceType(PriceProductTransfer $priceProductTransfer)
    {
        if ($priceProductTransfer->getPriceTypeName() === null) {
            $priceProductTransfer->setPriceTypeName(
                $this->priceConfig->getPriceTypeDefaultName()
            );
        }

        return $priceProductTransfer;
    }

    /**
     * @param int $idPriceProduct
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct
     */
    protected function getPriceProductById($idPriceProduct)
    {
        $priceProductCollection = $this->queryContainer->queryPriceProductEntity($idPriceProduct)->find();
        if ($priceProductCollection->count() === 0) {
            throw new Exception(self::ENTITY_NOT_FOUND);
        }

        return $this->queryContainer
            ->queryPriceProductEntity($idPriceProduct)
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isPriceTypeExistingForProductAbstract(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        $priceType = $this->reader->getPriceTypeByName($priceProductTransfer->getPriceTypeName());
        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductAbstract(
                $priceProductTransfer->getSkuProductAbstract(),
                $priceType,
                $moneyValueTransfer->getFkCurrency(),
                $moneyValueTransfer->getFkStore()
            );

        return $priceEntities->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return bool
     */
    protected function isPriceTypeExistingForProductConcrete(PriceProductTransfer $transferPriceProduct)
    {
        $transferPriceProduct->requireMoneyValue();

        $moneyValueTransfer = $transferPriceProduct->getMoneyValue();

        $priceType = $this->reader->getPriceTypeByName($transferPriceProduct->getPriceTypeName());
        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductConcrete(
                $transferPriceProduct->getSkuProduct(),
                $priceType,
                $moneyValueTransfer->getFkCurrency(),
                $moneyValueTransfer->getFkStore()
            );

        return $priceEntities->count() > 0;
    }

    /**
     * @param string $priceTypeName
     *
     * @throws \Spryker\Zed\PriceProduct\Business\Exception\UndefinedPriceTypeException
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceType
     */
    protected function getPriceTypeEntity($priceTypeName)
    {
        $priceTypeName = $this->priceTypeReader->handleDefaultPriceType($priceTypeName);
        $priceTypeEntity = $this->queryContainer
            ->queryPriceType($priceTypeName)
            ->findOne();

        if (!$priceTypeEntity) {
            throw new UndefinedPriceTypeException('Undefined product price type: ' . $priceTypeName);
        }

        return $priceTypeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function persistProductAbstractPriceCollection(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($productAbstractTransfer) {
            return $this->executePersistProductAbstractPriceCollectionTransaction($productAbstractTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductConcretePriceCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->handleDatabaseTransaction(function () use ($productConcreteTransfer) {
            return $this->executePersistProductConcretePriceCollectionTransaction($productConcreteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function executePersistProductAbstractPriceCollectionTransaction(ProductAbstractTransfer $productAbstractTransfer)
    {
        $idProductAbstract = $productAbstractTransfer
            ->requireIdProductAbstract()
            ->getIdProductAbstract();

        foreach ($productAbstractTransfer->getPrices() as $priceProductTransfer) {
            $this->persistProductAbstractPriceEntity($priceProductTransfer, $idProductAbstract);
            $this->persistPriceProductStore($priceProductTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function executePersistProductConcretePriceCollectionTransaction(ProductConcreteTransfer $productConcreteTransfer)
    {
        $idProductConcrete = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        foreach ($productConcreteTransfer->getPrices() as $priceProductTransfer) {
            $this->persistProductConcretePriceEntity($priceProductTransfer, $idProductConcrete);
            $this->persistPriceProductStore($priceProductTransfer);
        }

        return $productConcreteTransfer;
    }


    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceTransfer
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistProductAbstractPriceEntity(PriceProductTransfer $priceTransfer, $idProductAbstract)
    {
        $priceTypeEntity = $this->getPriceTypeEntity($priceTransfer->getPriceType()->getName());

        $priceProductEntity = $this->queryContainer
            ->queryPriceProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkPriceType($priceTypeEntity->getIdPriceType())
            ->findOneOrCreate();

        $priceProductEntity->setFkProductAbstract($idProductAbstract);
        $priceProductEntity->save();

        $priceTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $priceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceTransfer
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistProductConcretePriceEntity(PriceProductTransfer $priceTransfer, $idProductConcrete)
    {
        $priceTypeEntity = $this->getPriceTypeEntity($priceTransfer->getPriceType()->getName());

        $priceProductEntity = $this->queryContainer
            ->queryPriceProduct()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkPriceType($priceTypeEntity->getIdPriceType())
            ->filterByFkProductAbstract(null, Criteria::ISNULL)
            ->findOneOrCreate();

        $priceProductEntity->setFkProduct($idProductConcrete);
        $priceProductEntity->save();

        $priceTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $priceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function persistPriceProductStore(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        $priceProduceStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkCurrency($moneyValueTransfer->getFkCurrency())
            ->filterByFkStore($moneyValueTransfer->getFkStore())
            ->findOneOrCreate();

        $priceProduceStoreEntity->fromArray($moneyValueTransfer->toArray());
        $priceProduceStoreEntity->setFkPriceProduct($priceProductTransfer->getIdPriceProduct());
        $priceProduceStoreEntity->setNetPrice($moneyValueTransfer->getNetAmount());
        $priceProduceStoreEntity->setGrossPrice($moneyValueTransfer->getGrossAmount());
        $priceProduceStoreEntity->save();
    }
}
