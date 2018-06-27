<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\PriceProduct\PriceProductConstants;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class PriceProductAbstractWriter extends BaseProductPriceWriter implements PriceProductAbstractWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    protected $priceTypeReader;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $priceProductQueryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface
     */
    protected $priceProductDefaultWriter;

    /**
     * @var array|\Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]
     */
    protected $priceDimensionAbstractSaverPlugins;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $priceProductEntityManager;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceTypeReader
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface $priceProductDefaultWriter
     * @param \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[] $priceDimensionAbstractSaverPlugins
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     */
    public function __construct(
        PriceProductTypeReaderInterface $priceTypeReader,
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductDefaultWriterInterface $priceProductDefaultWriter,
        array $priceDimensionAbstractSaverPlugins,
        PriceProductEntityManagerInterface $priceProductEntityManager
    ) {
        $this->priceTypeReader = $priceTypeReader;
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductDefaultWriter = $priceProductDefaultWriter;
        $this->priceDimensionAbstractSaverPlugins = $priceDimensionAbstractSaverPlugins;
        $this->priceProductEntityManager = $priceProductEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function persistProductAbstractPriceCollection(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {

        return $this->handleDatabaseTransaction(function () use ($productAbstractTransfer) {
            return $this->executePersistProductAbstractPriceCollectionTransaction($productAbstractTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function executePersistProductAbstractPriceCollectionTransaction(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        foreach ($productAbstractTransfer->getPrices() as $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            if ($this->isEmptyMoneyValue($moneyValueTransfer)) {
                continue;
            }

            $this->executePersistProductAbstractPrice($productAbstractTransfer, $priceProductTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function executePersistProductAbstractPrice(
        ProductAbstractTransfer $productAbstractTransfer,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $idProductAbstract = $productAbstractTransfer
            ->requireIdProductAbstract()
            ->getIdProductAbstract();

        $priceProductTransfer->requirePriceDimension();

        $this->persistProductAbstractPriceEntity($priceProductTransfer, $idProductAbstract);
        $priceProductTransfer->setIdProductAbstract($idProductAbstract);
        $priceProductTransfer = $this->executePriceDimensionAbstractSaverPlugins($priceProductTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function executePriceDimensionAbstractSaverPlugins(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {

        $priceDimensionType = $priceProductTransfer->getPriceDimension()->getType();
        if ($priceDimensionType === PriceProductConstants::PRICE_DIMENSION_DEFAULT) {
            $priceProductDefaultEntityTransfer = $this->priceProductDefaultWriter->persistPriceProductDefault($priceProductTransfer);
            $priceProductTransfer->getPriceDimension()->setIdPriceProductDefault(
                $priceProductDefaultEntityTransfer->getIdPriceProductDefault()
            );

            return $priceProductTransfer;
        }

        foreach ($this->priceDimensionAbstractSaverPlugins as $priceDimensionAbstractSaverPlugin) {
            if ($priceDimensionAbstractSaverPlugin->getDimensionName() !== $priceDimensionType) {
                continue;
            }

            return $priceDimensionAbstractSaverPlugin->savePrice($priceProductTransfer);
        }

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistProductAbstractPriceEntity(
        PriceProductTransfer $priceProductTransfer,
        $idProductAbstract
    ): PriceProductTransfer {

        $priceTypeEntity = $this->priceTypeReader->getPriceTypeByName($priceProductTransfer->getPriceType()->getName());

        $priceProductEntity = $this->priceProductQueryContainer
            ->queryPriceProductForAbstractProduct($idProductAbstract, $priceTypeEntity->getIdPriceType())
            ->findOneOrCreate();

        $priceProductEntity->setFkProductAbstract($idProductAbstract)
            ->save();

        $priceProductTransfer->setIdPriceProduct($priceProductEntity->getIdPriceProduct());

        return $priceProductTransfer;
    }
}
