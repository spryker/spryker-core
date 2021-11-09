<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Spryker\Zed\PriceProduct\Business\Exception\MissingPriceException;
use Spryker\Zed\PriceProduct\Business\Exception\ProductPriceChangeException;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class Writer implements WriterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var string
     */
    public const TOUCH_PRODUCT = 'product';

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
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface
     */
    protected $priceProductStoreWriter;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceConfig
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceTypeReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface $priceProductStoreWriter
     */
    public function __construct(
        PriceProductQueryContainerInterface $queryContainer,
        PriceProductToTouchFacadeInterface $touchFacade,
        PriceProductConfig $priceConfig,
        PriceProductToProductFacadeInterface $productFacade,
        PriceProductTypeReaderInterface $priceTypeReader,
        PriceProductStoreWriterInterface $priceProductStoreWriter
    ) {
        $this->queryContainer = $queryContainer;
        $this->touchFacade = $touchFacade;
        $this->priceConfig = $priceConfig;
        $this->productFacade = $productFacade;
        $this->priceTypeReader = $priceTypeReader;
        $this->priceProductStoreWriter = $priceProductStoreWriter;
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
        $priceProductTransfer
            ->requireMoneyValue()
            ->requirePriceDimension();

        $priceProductTransfer = $this->setPriceType($priceProductTransfer);
        if ($this->havePriceAlreadyAssignedForCouple($priceProductTransfer)) {
            throw new ProductPriceChangeException('This couple product price type is already set');
        }

        $this->loadPriceProductTransfer($priceProductTransfer);

        $priceProductTransfer = $this->savePriceProductEntity($priceProductTransfer, new SpyPriceProduct());
        if ($priceProductTransfer->getIdProduct()) {
            /** @var int $idProduct */
            $idProduct = $priceProductTransfer->getIdProduct();
            $this->insertTouchRecord(static::TOUCH_PRODUCT, $idProduct);
        }

        $priceProductTransfer->setIdPriceProduct($priceProductTransfer->getIdPriceProduct());

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

        $this->loadPriceProductTransfer($priceProductTransfer);

        /** @var int $idPriceProduct */
        $idPriceProduct = $priceProductTransfer->requireIdPriceProduct()->getIdPriceProduct();
        $priceProductEntity = $this->getPriceProductById($idPriceProduct);
        $this->savePriceProductEntity($priceProductTransfer, $priceProductEntity);

        if ($priceProductTransfer->getIdProduct()) {
            /** @var int $idProduct */
            $idProduct = $priceProductTransfer->getIdProduct();
            $this->insertTouchRecord(static::TOUCH_PRODUCT, $idProduct);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function loadPriceProductTransfer(PriceProductTransfer $priceProductTransfer)
    {
        $this->loadProductAbstractIdForPriceProductTransfer($priceProductTransfer);
        $this->loadProductConcreteIdForPriceProductTransfer($priceProductTransfer);
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

        /** @var string $skuProductAbstract */
        $skuProductAbstract = $priceProductTransfer->requireSkuProductAbstract()->getSkuProductAbstract();
        $priceProductTransfer->setIdProductAbstract(
            $this->productFacade->findProductAbstractIdBySku($skuProductAbstract),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function loadProductConcreteIdForPriceProductTransfer(PriceProductTransfer $priceProductTransfer)
    {
        /** @var string $skuProduct */
        $skuProduct = $priceProductTransfer->getSkuProduct();

        if (
            $priceProductTransfer->getIdProduct() === null &&
            $this->productFacade->hasProductConcrete($skuProduct)
        ) {
            /** @var int $idProduct */
            $idProduct = $this->productFacade->findProductConcreteIdBySku($skuProduct);
            $priceProductTransfer->setIdProduct($idProduct);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function savePriceProductEntity(PriceProductTransfer $priceProductTransfer, SpyPriceProduct $priceProductEntity): PriceProductTransfer
    {
        /** @var string $priceTypeName */
        $priceTypeName = $priceProductTransfer->requirePriceTypeName()->getPriceTypeName();
        $priceType = $this->priceTypeReader->getPriceTypeByName($priceTypeName);
        $priceProductEntity->setPriceType($priceType);

        if ($priceProductTransfer->getIdProduct()) {
            $priceProductEntity->setFkProduct($priceProductTransfer->getIdProduct());
        } else {
            $priceProductEntity->setFkProductAbstract($priceProductTransfer->getIdProductAbstract());
        }

        $priceProductEntity->save();

        $priceProductTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $this->priceProductStoreWriter->persistPriceProductStore($priceProductTransfer);
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
                $this->priceConfig->getPriceTypeDefaultName(),
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
                    $idPriceProduct,
                ),
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

        /** @var string $skuProductAbstract */
        $skuProductAbstract = $priceProductTransfer->requireSkuProductAbstract()->getSkuProductAbstract();
        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductAbstract(
                $skuProductAbstract,
                $priceProductCriteriaTransfer,
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
        /** @var string $skuProduct */
        $skuProduct = $priceProductTransfer->requireSkuProduct()->getSkuProduct();

        $priceProductCriteriaTransfer = $this->createPriceProductCriteriaFor($priceProductTransfer);

        $priceEntities = $this->queryContainer
            ->queryPriceEntityForProductConcrete(
                $skuProduct,
                $priceProductCriteriaTransfer,
            );

        return ($priceEntities->count() > 0);
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
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $priceProductTransfer->requireMoneyValue()->getMoneyValue();
        /** @var string $priceTypeName */
        $priceTypeName = $priceProductTransfer->requirePriceTypeName()->getPriceTypeName();
        $priceTypeEntity = $this->priceTypeReader->getPriceTypeByName($priceTypeName);

        return (new PriceProductCriteriaTransfer())
            ->setIdCurrency($moneyValueTransfer->getFkCurrency())
            ->setIdStore($moneyValueTransfer->getFkStore())
            ->setPriceType($priceTypeEntity->getName());
    }
}
