<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;
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
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceTypeReader
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface $priceProductDefaultWriter
     * @param \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[] $priceDimensionAbstractSaverPlugins
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $config
     */
    public function __construct(
        PriceProductTypeReaderInterface $priceTypeReader,
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductDefaultWriterInterface $priceProductDefaultWriter,
        array $priceDimensionAbstractSaverPlugins,
        PriceProductEntityManagerInterface $priceProductEntityManager,
        PriceProductConfig $config
    ) {
        $this->priceTypeReader = $priceTypeReader;
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductDefaultWriter = $priceProductDefaultWriter;
        $this->priceDimensionAbstractSaverPlugins = $priceDimensionAbstractSaverPlugins;
        $this->priceProductEntityManager = $priceProductEntityManager;
        $this->config = $config;
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

        if (!$priceProductTransfer->getPriceDimension()) {
            $priceProductTransfer->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType($this->config->getPriceDimensionDefault())
            );
        }

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

        if ($priceDimensionType === $this->config->getPriceDimensionDefault()) {
            return $this->persistPriceProductIfDimensionTypeDefault($priceProductTransfer);
        }

        return $this->savePrice($priceProductTransfer, $priceDimensionType);
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param string $priceDimensionType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function savePrice(
        PriceProductTransfer $priceProductTransfer,
        string $priceDimensionType
    ): PriceProductTransfer {
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
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistPriceProductIfDimensionTypeDefault(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {

        $priceProductDefaultEntityTransfer = $this->priceProductDefaultWriter->persistPriceProductDefault($priceProductTransfer);
        $priceProductTransfer->getPriceDimension()->setIdPriceProductDefault(
            $priceProductDefaultEntityTransfer->getIdPriceProductDefault()
        );

        return $priceProductTransfer;
    }
}
