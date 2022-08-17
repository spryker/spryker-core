<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter;

use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductStoreWriterPluginExecutor implements PriceProductStoreWriterPluginExecutorInterface
{
    /**
     * @var array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductStorePreDeletePluginInterface>
     */
    protected $priceProductStorePreDeletePlugins;

    /**
     * @var array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface>
     */
    protected $priceDimensionAbstractSaverPlugins;

    /**
     * @var array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface>
     */
    protected $priceDimensionConcreteSaverPlugins;

    /**
     * @var array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface>
     */
    protected $orphanPriceProductStoreRemovalVoterPlugins;

    /**
     * @param array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductStorePreDeletePluginInterface> $priceProductStorePreDeletePlugins
     * @param array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface> $priceDimensionAbstractSaverPlugins
     * @param array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface> $priceDimensionConcreteSaverPlugins
     * @param array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface> $orphanPriceProductStoreRemovalVoterPlugins
     */
    public function __construct(
        array $priceProductStorePreDeletePlugins,
        array $priceDimensionAbstractSaverPlugins,
        array $priceDimensionConcreteSaverPlugins,
        array $orphanPriceProductStoreRemovalVoterPlugins
    ) {
        $this->priceProductStorePreDeletePlugins = $priceProductStorePreDeletePlugins;
        $this->priceDimensionAbstractSaverPlugins = $priceDimensionAbstractSaverPlugins;
        $this->priceDimensionConcreteSaverPlugins = $priceDimensionConcreteSaverPlugins;
        $this->orphanPriceProductStoreRemovalVoterPlugins = $orphanPriceProductStoreRemovalVoterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function executePriceDimensionAbstractSaverPlugins(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceDimensionTransfer */
        $priceDimensionTransfer = $priceProductTransfer->requirePriceDimension()->getPriceDimension();
        $priceDimensionType = $priceDimensionTransfer->getType();

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
    public function executePriceDimensionConcreteSaverPlugins(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        /** @var \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceDimensionTransfer */
        $priceDimensionTransfer = $priceProductTransfer->requirePriceDimension()->getPriceDimension();
        $priceDimensionType = $priceDimensionTransfer->getType();

        foreach ($this->priceDimensionConcreteSaverPlugins as $priceDimensionConcreteSaverPlugin) {
            if ($priceDimensionConcreteSaverPlugin->getDimensionName() !== $priceDimensionType) {
                continue;
            }

            return $priceDimensionConcreteSaverPlugin->savePrice($priceProductTransfer);
        }

        return $priceProductTransfer;
    }

    /**
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function executePriceProductStorePreDeletePlugins(int $idPriceProductStore): void
    {
        foreach ($this->priceProductStorePreDeletePlugins as $priceProductStorePreDeletePlugin) {
            $priceProductStorePreDeletePlugin->preDelete($idPriceProductStore);
        }
    }

    /**
     * @return bool|null
     */
    public function executeOrphanPriceProductStoreRemovalVoterPlugins(): ?bool
    {
        $isRemovalEnabled = null;

        foreach ($this->orphanPriceProductStoreRemovalVoterPlugins as $orphanPriceProductStoreRemovalVoterPlugin) {
            $isRemovalEnabled = $orphanPriceProductStoreRemovalVoterPlugin->isRemovalEnabled();
            if (!$isRemovalEnabled) {
                return false;
            }
        }

        return $isRemovalEnabled;
    }
}
