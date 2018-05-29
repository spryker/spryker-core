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
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface;
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
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface
     */
    protected $priceProductStoreWriter;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface
     */
    protected $priceProductDefaultWriter;

    /**
     * @var array|\Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]
     */
    protected $priceDimensionAbstractSaverPlugins;

    /**
     * @var array|\Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[]
     */
    protected $priceDimensionConcreteSaverPlugins;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceConfig
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceTypeReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriterInterface $priceProductStoreWriter
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface $priceProductDefaultWriter
     * @param array $priceDimensionAbstractSaverPlugins
     * @param array $priceDimensionConcreteSaverPlugins
     */
    public function __construct(
        PriceProductQueryContainerInterface $queryContainer,
        PriceProductToTouchFacadeInterface $touchFacade,
        PriceProductConfig $priceConfig,
        PriceProductToProductFacadeInterface $productFacade,
        PriceProductTypeReaderInterface $priceTypeReader,
        PriceProductStoreWriterInterface $priceProductStoreWriter,
        PriceProductDefaultWriterInterface $priceProductDefaultWriter,
        array $priceDimensionAbstractSaverPlugins,
        array $priceDimensionConcreteSaverPlugins
    ) {
        $this->queryContainer = $queryContainer;
        $this->touchFacade = $touchFacade;
        $this->priceConfig = $priceConfig;
        $this->productFacade = $productFacade;
        $this->priceTypeReader = $priceTypeReader;
        $this->priceProductStoreWriter = $priceProductStoreWriter;
        $this->priceProductDefaultWriter = $priceProductDefaultWriter;
        $this->priceDimensionAbstractSaverPlugins = $priceDimensionAbstractSaverPlugins;
        $this->priceDimensionConcreteSaverPlugins = $priceDimensionConcreteSaverPlugins;
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
            $this->insertTouchRecord(static::TOUCH_PRODUCT, $priceProductTransfer->getIdProduct());
        }

        if ($priceProductTransfer->getIdProduct()) {
            $priceProductTransfer = $this->executePriceDimensionSaverPlugins($priceProductTransfer, $this->priceDimensionConcreteSaverPlugins);
        } elseif ($priceProductTransfer->getIdProductAbstract()) {
            $priceProductTransfer = $this->executePriceDimensionSaverPlugins($priceProductTransfer, $this->priceDimensionAbstractSaverPlugins);
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

        $priceProductEntity = $this->getPriceProductById($priceProductTransfer->getIdPriceProduct());
        $this->savePriceProductEntity($priceProductTransfer, $priceProductEntity);

        if ($priceProductTransfer->getIdProduct()) {
            $this->insertTouchRecord(self::TOUCH_PRODUCT, $priceProductTransfer->getIdProduct());
        }

        if ($priceProductTransfer->getIdProduct()) {
            $this->executePriceDimensionSaverPlugins($priceProductTransfer, $this->priceDimensionConcreteSaverPlugins);
        } elseif ($priceProductTransfer->getIdProductAbstract()) {
            $this->executePriceDimensionSaverPlugins($priceProductTransfer, $this->priceDimensionAbstractSaverPlugins);
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
     * @return \Generated\Shared\Transfer\PriceProductTransfer
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $priceDimensionSaverPlugins
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function executePriceDimensionSaverPlugins(
        PriceProductTransfer $priceProductTransfer,
        array $priceDimensionSaverPlugins
    ): PriceProductTransfer {

        $priceDimensionType = $priceProductTransfer->getPriceDimension()->getType();
        if ($priceDimensionType === PriceProductConfig::PRICE_DIMENSION_DEFAULT) {
            $priceProductDefaultEntityTransfer = $this->priceProductDefaultWriter->persistPriceProductDefault($priceProductTransfer);
            $priceProductTransfer->getPriceDimension()->setIdPriceProductDefault(
                $priceProductDefaultEntityTransfer->getIdPriceProductDefault()
            );

            return $priceProductTransfer;
        }

        foreach ($priceDimensionSaverPlugins as $priceDimensionSaverPlugin) {
            if ($priceDimensionSaverPlugin->getDimensionName() !== $priceDimensionType) {
                continue;
            }

            return $priceDimensionSaverPlugin->savePrice($priceProductTransfer);
        }

        return $priceProductTransfer;
    }
}
