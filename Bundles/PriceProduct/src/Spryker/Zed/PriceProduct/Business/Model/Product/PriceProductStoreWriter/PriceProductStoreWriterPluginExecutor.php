<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class PriceProductStoreWriterPluginExecutor implements PriceProductStoreWriterPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductStorePreDeletePluginInterface[]
     */
    protected $priceProductStorePreDeletePlugins;

    /**
     * @var array|\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]
     */
    protected $priceDimensionAbstractSaverPlugins;

    /**
     * @var array|\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[]
     */
    protected $priceDimensionConcreteSaverPlugins;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $priceConfig;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface
     */
    protected $priceProductDefaultWriter;

    /**
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductStorePreDeletePluginInterface[] $priceProductStorePreDeletePlugins
     * @param array $priceDimensionAbstractSaverPlugins
     * @param array $priceDimensionConcreteSaverPlugins
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceConfig
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductDefaultWriterInterface $priceProductDefaultWriter
     */
    public function __construct(
        array $priceProductStorePreDeletePlugins,
        array $priceDimensionAbstractSaverPlugins,
        array $priceDimensionConcreteSaverPlugins,
        PriceProductConfig $priceConfig,
        PriceProductDefaultWriterInterface $priceProductDefaultWriter
    ) {
        $this->priceProductStorePreDeletePlugins = $priceProductStorePreDeletePlugins;
        $this->priceDimensionAbstractSaverPlugins = $priceDimensionAbstractSaverPlugins;
        $this->priceDimensionConcreteSaverPlugins = $priceDimensionConcreteSaverPlugins;
        $this->priceConfig = $priceConfig;
        $this->priceProductDefaultWriter = $priceProductDefaultWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function runPriceDimensionSaverPlugins(PriceProductTransfer $priceProductTransfer): void
    {
        if ($priceProductTransfer->getIdProduct()) {
            $this->executePriceDimensionSaverPlugins($priceProductTransfer, $this->priceDimensionConcreteSaverPlugins);
        } elseif ($priceProductTransfer->getIdProductAbstract()) {
            $this->executePriceDimensionSaverPlugins($priceProductTransfer, $this->priceDimensionAbstractSaverPlugins);
        }
    }

    /**
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function runPriceProductStorePreDeletePlugins(int $idPriceProductStore): void
    {
        foreach ($this->priceProductStorePreDeletePlugins as $priceProductStorePreDeletePlugin) {
            $priceProductStorePreDeletePlugin->preDelete($idPriceProductStore);
        }
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

        if ($priceDimensionType === $this->priceConfig->getPriceDimensionDefault()) {
            return $this->persistPriceProductIfDimensionTypeDefault($priceProductTransfer);
        }

        return $this->savePrice($priceProductTransfer, $priceDimensionSaverPlugins, $priceDimensionType);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistPriceProductIfDimensionTypeDefault(PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer
    {
        $priceProductDefaultEntityTransfer = $this->priceProductDefaultWriter->persistPriceProductDefault($priceProductTransfer);
        $priceProductTransfer->getPriceDimension()->setIdPriceProductDefault(
            $priceProductDefaultEntityTransfer->getIdPriceProductDefault()
        );

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $priceDimensionSaverPlugins
     * @param string $priceDimensionType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function savePrice(
        PriceProductTransfer $priceProductTransfer,
        array $priceDimensionSaverPlugins,
        string $priceDimensionType
    ): PriceProductTransfer {

        foreach ($priceDimensionSaverPlugins as $priceDimensionSaverPlugin) {
            if ($priceDimensionSaverPlugin->getDimensionName() !== $priceDimensionType) {
                continue;
            }

            return $priceDimensionSaverPlugin->savePrice($priceProductTransfer);
        }

        return $priceProductTransfer;
    }
}
