<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Price\Persistence\SpyPriceProduct;
use Spryker\Zed\Price\Business\Exception\ProductPriceChangeException;
use Spryker\Zed\Price\Business\Exception\UndefinedPriceTypeException;
use Spryker\Zed\Price\Dependency\Facade\PriceToTouchInterface;
use Spryker\Zed\Price\Persistence\PriceQueryContainerInterface;
use Spryker\Zed\Price\PriceConfig;
use Spryker\Zed\Propel\Business\Runtime\ActiveQuery\Criteria;

class Writer implements WriterInterface
{

    const TOUCH_PRODUCT = 'product';
    const ENTITY_NOT_FOUND = 'entity not found';

    /**
     * @var \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Price\Business\Model\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\Price\Dependency\Facade\PriceToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Price\PriceConfig
     */
    protected $priceConfig;

    /**
     * @param \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Price\Business\Model\ReaderInterface $reader
     * @param \Spryker\Zed\Price\Dependency\Facade\PriceToTouchInterface $touchFacade
     * @param \Spryker\Zed\Price\PriceConfig $priceConfig
     */
    public function __construct(
        PriceQueryContainerInterface $queryContainer,
        ReaderInterface $reader,
        PriceToTouchInterface $touchFacade,
        PriceConfig $priceConfig
    ) {
        $this->queryContainer = $queryContainer;
        $this->reader = $reader;
        $this->touchFacade = $touchFacade;
        $this->priceConfig = $priceConfig;
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceType
     */
    public function createPriceType($name)
    {
        $priceTypeEntity = $this->queryContainer->queryPriceType($name)->findOneOrCreate();
        $priceTypeEntity->setName($name)->save();

        return $priceTypeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @throws \Spryker\Zed\Price\Business\Exception\ProductPriceChangeException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    public function createPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
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
     * @throws \Spryker\Zed\Price\Business\Exception\ProductPriceChangeException
     *
     * @return void
     */
    public function setPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
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
        if ($priceProductTransfer->getIdProductAbstract() === null) {
            $priceProductTransfer->setIdProductAbstract(
                $this->reader->getProductAbstractIdBySku($priceProductTransfer->getSkuProductAbstract())
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function loadProductConcreteIdForPriceProductTransfer(PriceProductTransfer $priceProductTransfer)
    {
        if ($priceProductTransfer->getIdProduct() === null &&
            $this->reader->hasProductConcrete($priceProductTransfer->getSkuProduct())
        ) {
            $priceProductTransfer->setIdProduct(
                $this->reader->getProductConcreteIdBySku($priceProductTransfer->getSkuProduct())
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Orm\Zed\Price\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function savePriceProductEntity(PriceProductTransfer $priceProductTransfer, SpyPriceProduct $priceProductEntity)
    {
        $priceType = $this->reader->getPriceTypeByName($priceProductTransfer->getPriceTypeName());
        $priceProductEntity
            ->setPriceType($priceType)
            ->setPrice($priceProductTransfer->getPrice());

        if ($priceProductTransfer->getIdProduct()) {
            $priceProductEntity->setFkProduct($priceProductTransfer->getIdProduct());
        } else {
            $priceProductEntity->setFkProductAbstract($priceProductTransfer->getIdProductAbstract());
        }

        $priceProductEntity->save();

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
     * @return \Orm\Zed\Price\Persistence\SpyPriceProduct
     */
    protected function getPriceProductById($idPriceProduct)
    {
        $priceProductEntity = $this->queryContainer->queryPriceProductEntity($idPriceProduct)->find();
        if (!count($priceProductEntity) > 0) {
            throw new \Exception(self::ENTITY_NOT_FOUND);
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
        $priceType = $this->reader->getPriceTypeByName($priceProductTransfer->getPriceTypeName());
        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductAbstract($priceProductTransfer->getSkuProductAbstract(), $priceType);

        return $priceEntities->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return bool
     */
    protected function isPriceTypeExistingForProductConcrete(PriceProductTransfer $transferPriceProduct)
    {
        $priceType = $this->reader->getPriceTypeByName($transferPriceProduct->getPriceTypeName());
        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductConcrete($transferPriceProduct->getSkuProduct(), $priceType);

        return $priceEntities->count() > 0;
    }

    /**
     * @param string $priceTypeName
     *
     * @throws \Spryker\Zed\Price\Business\Exception\UndefinedPriceTypeException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceType
     */
    protected function getPriceTypeEntity($priceTypeName)
    {
        $priceTypeName = $this->reader->handleDefaultPriceType($priceTypeName);
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
     * @return ProductAbstractTransfer
     */
    public function persistProductAbstractPrice(ProductAbstractTransfer $productAbstractTransfer)
    {
        if (!$productAbstractTransfer->getPrice()) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->requireIdProductAbstract();

        $priceTransfer = $productAbstractTransfer->getPrice();
        $priceTypeEntity = $this->getPriceTypeEntity($priceTransfer->getPriceTypeName());

        $priceProductEntity = $this->queryContainer
            ->queryPriceProduct()
            ->filterByFkProductAbstract($priceTransfer->getIdProductAbstract())
            ->filterByFkPriceType($priceTypeEntity->getIdPriceType())
            ->findOneOrCreate();

        $priceProductEntity->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract());
        $priceProductEntity->setPrice($priceTransfer->getPrice());
        $priceProductEntity->save();

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function persistProductConcretePrice(ProductConcreteTransfer $productConcreteTransfer)
    {
        if (!$productConcreteTransfer->getPrice()) {
            return $productConcreteTransfer;
        }

        $productConcreteTransfer->requireIdProductConcrete();
        $priceTransfer = $productConcreteTransfer->getPrice();
        $priceTypeEntity = $this->getPriceTypeEntity($priceTransfer->getPriceTypeName());

        $priceProductEntity = $this->queryContainer
            ->queryPriceProduct()
            ->filterByFkProduct($priceTransfer->getIdProduct())
            ->filterByFkPriceType($priceTypeEntity->getIdPriceType())
            ->filterByFkProductAbstract(null, Criteria::ISNULL)
            ->findOneOrCreate();

        $priceProductEntity->setFkProduct($productConcreteTransfer->getIdProductConcrete());
        $priceProductEntity->setPrice($priceTransfer->getPrice());
        $priceProductEntity->save();

        return $productConcreteTransfer;
    }

}
