<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Spryker\Zed\PriceProduct\Business\Exception\MissingPriceException;
use Spryker\Zed\PriceProduct\Business\Exception\ProductPriceChangeException;
use Spryker\Zed\PriceProduct\Business\Exception\UndefinedPriceTypeException;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class Writer implements WriterInterface
{
    use DatabaseTransactionHandlerTrait;

    const TOUCH_PRODUCT = 'product';

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $priceConfig;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    protected $priceTypeReader;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface
     */
    protected $priceProductMapper;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceConfig
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceTypeReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface $priceProductMapper
     */
    public function __construct(
        PriceProductQueryContainerInterface $queryContainer,
        PriceProductToTouchFacadeInterface $touchFacade,
        PriceProductConfig $priceConfig,
        PriceProductToProductFacadeInterface $productFacade,
        PriceProductTypeReaderInterface $priceTypeReader,
        PriceProductMapperInterface $priceProductMapper
    ) {
        $this->queryContainer = $queryContainer;
        $this->touchFacade = $touchFacade;
        $this->priceConfig = $priceConfig;
        $this->productFacade = $productFacade;
        $this->priceTypeReader = $priceTypeReader;
        $this->priceProductMapper = $priceProductMapper;
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function createPriceType($name)
    {
        $priceTypeEntity = $this->queryContainer
            ->queryPriceType($name)
            ->findOneOrCreate();

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
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireMoneyValue();

        $priceProductTransfer = $this->setPriceType($priceProductTransfer);
        if ($this->havePriceAlreadyAssignedForCouple($priceProductTransfer)) {
            throw new ProductPriceChangeException('This couple product price type is already set');
        }

        $this->loadProductAbstractIdForPriceProductTransfer($priceProductTransfer);
        $this->loadProductConcreteIdForPriceProductTransfer($priceProductTransfer);

        $pricePriceProductStoreEntity = $this->savePriceProductEntity($priceProductTransfer, new SpyPriceProduct());
        if ($priceProductTransfer->getIdProduct()) {
            $this->insertTouchRecord(static::TOUCH_PRODUCT, $priceProductTransfer->getIdProduct());
        }

        $priceProductTransfer->setIdPriceProduct($pricePriceProductStoreEntity->getPriceProduct()->getIdPriceProduct());

        return $priceProductTransfer;
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

        if (!$this->havePriceInAnyCouple($priceProductTransfer)) {
            throw new ProductPriceChangeException('There is no price assigned for selected product!');
        }

        $this->loadProductAbstractIdForPriceProductTransfer($priceProductTransfer);
        $this->loadProductConcreteIdForPriceProductTransfer($priceProductTransfer);

        $priceProductEntity = $this->getPriceProductById($priceProductTransfer->getIdPriceProduct());
        $this->savePriceProductEntity($priceProductTransfer, $priceProductEntity);

        if ($priceProductTransfer->getIdProduct()) {
            $this->insertTouchRecord(self::TOUCH_PRODUCT, $priceProductTransfer->getIdProduct());
        }
        return;
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
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function savePriceProductEntity(PriceProductTransfer $priceProductTransfer, SpyPriceProduct $priceProductEntity)
    {
        $priceType = $this->priceTypeReader->getPriceTypeByName($priceProductTransfer->getPriceTypeName());
        $priceProductEntity->setPriceType($priceType);

        if ($priceProductTransfer->getIdProduct()) {
            $priceProductEntity->setFkProduct($priceProductTransfer->getIdProduct());
        } else {
            $priceProductEntity->setFkProductAbstract($priceProductTransfer->getIdProductAbstract());
        }

        $priceProductEntity->save();

        $priceProductTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $this->persistPriceProductStore($priceProductTransfer);
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
     * @throws \Spryker\Zed\PriceProduct\Business\Exception\MissingPriceException
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct
     */
    protected function getPriceProductById($idPriceProduct)
    {
        $priceProductEntity = $this->queryContainer
            ->queryPriceProductEntity($idPriceProduct)
            ->findOne();

        if ($priceProductEntity === null) {
            throw new MissingPriceException(
                sprintf(
                    'There are no prices for product with id "%s"',
                    $idPriceProduct
                )
            );
        }

        return $priceProductEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isPriceTypeExistingForProductAbstract(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireSkuProductAbstract();

        $priceProductCriteriaTransfer = $this->createPriceProductCriteriaFor($priceProductTransfer);

        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductAbstract(
                $priceProductTransfer->getSkuProductAbstract(),
                $priceProductCriteriaTransfer
            )->findOne();

        return ($priceEntities !== null);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isPriceTypeExistingForProductConcrete(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireSkuProduct();

        $priceProductCriteriaTransfer = $this->createPriceProductCriteriaFor($priceProductTransfer);

        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductConcrete(
                $priceProductTransfer->getSkuProduct(),
                $priceProductCriteriaTransfer
            );

        return ($priceEntities !== null);
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
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            if ($this->isEmptyMoneyValue($moneyValueTransfer)) {
                continue;
            }

            $this->persistProductAbstractPriceEntity($priceProductTransfer, $idProductAbstract);
            $this->persistPriceProductStore($priceProductTransfer);
            $priceProductTransfer->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());
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
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            if ($this->isEmptyMoneyValue($moneyValueTransfer)) {
                continue;
            }

            $this->persistProductConcretePriceEntity($priceProductTransfer, $idProductConcrete);
            $this->persistPriceProductStore($priceProductTransfer);

            $priceProductTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());
            $priceProductTransfer->setIdProduct($productConcreteTransfer->getIdProductConcrete());
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
            ->queryPriceProductForAbstractProduct($idProductAbstract, $priceTypeEntity->getIdPriceType())
            ->findOneOrCreate();

        $priceProductEntity->setFkProductAbstract($idProductAbstract)
            ->save();

        $priceTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $priceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistProductConcretePriceEntity(PriceProductTransfer $priceProductTransfer, $idProductConcrete)
    {
        $priceTypeEntity = $this->getPriceTypeEntity($priceProductTransfer->getPriceType()->getName());

        $priceProductEntity = $this->queryContainer
            ->queryPriceProductForConcreteProductBy($idProductConcrete, $priceTypeEntity->getIdPriceType())
            ->findOneOrCreate();

        $priceProductEntity->setFkProduct($idProductConcrete)
            ->save();

        $priceProductTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function persistPriceProductStore(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        $priceProduceStoreEntity = $this->queryContainer
            ->queryPriceProductStoreByProductCurrencyStore(
                $priceProductTransfer->getIdPriceProduct(),
                $moneyValueTransfer->getFkCurrency(),
                $moneyValueTransfer->getFkStore()
            )->findOneOrCreate();

        $priceProduceStoreEntity->fromArray($moneyValueTransfer->toArray());

        $priceProduceStoreEntity->setFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->setNetPrice($moneyValueTransfer->getNetAmount())
            ->setGrossPrice($moneyValueTransfer->getGrossAmount())
            ->save();

        $moneyValueTransfer->setIdEntity($priceProduceStoreEntity->getIdPriceProductStore());

        return $priceProduceStoreEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return bool
     */
    protected function isEmptyMoneyValue(MoneyValueTransfer $moneyValueTransfer)
    {
        return (!$moneyValueTransfer->getIdEntity() && $moneyValueTransfer->getNetAmount() === null && $moneyValueTransfer->getGrossAmount() === null);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function havePriceAlreadyAssignedForCouple(PriceProductTransfer $priceProductTransfer)
    {
        return $this->isPriceTypeExistingForProductAbstract($priceProductTransfer)
            && $this->isPriceTypeExistingForProductConcrete($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function havePriceInAnyCouple(PriceProductTransfer $priceProductTransfer)
    {
        return $this->isPriceTypeExistingForProductConcrete($priceProductTransfer)
            || $this->isPriceTypeExistingForProductAbstract($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function createPriceProductCriteriaFor(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
        $priceTypeEntity = $this->priceTypeReader->getPriceTypeByName($priceProductTransfer->getPriceTypeName());

        return (new PriceProductCriteriaTransfer())
            ->setIdCurrency($moneyValueTransfer->getFkCurrency())
            ->setIdStore($moneyValueTransfer->getFkStore())
            ->setPriceType($priceTypeEntity->getName());
    }
}
