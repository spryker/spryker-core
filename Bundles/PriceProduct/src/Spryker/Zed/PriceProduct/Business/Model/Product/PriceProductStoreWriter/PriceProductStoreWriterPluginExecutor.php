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
     * @var \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductStorePreDeletePluginInterface[]
     */
    protected $priceProductStorePreDeletePlugins;

    /**
     * @var \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]
     */
    protected $priceDimensionAbstractSaverPlugins;

    /**
     * @var \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[]
     */
    protected $priceDimensionConcreteSaverPlugins;

    /**
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductStorePreDeletePluginInterface[] $priceProductStorePreDeletePlugins
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[] $priceDimensionAbstractSaverPlugins
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[] $priceDimensionConcreteSaverPlugins
     */
    public function __construct(
        array $priceProductStorePreDeletePlugins,
        array $priceDimensionAbstractSaverPlugins,
        array $priceDimensionConcreteSaverPlugins
    ) {
        $this->priceProductStorePreDeletePlugins = $priceProductStorePreDeletePlugins;
        $this->priceDimensionAbstractSaverPlugins = $priceDimensionAbstractSaverPlugins;
        $this->priceDimensionConcreteSaverPlugins = $priceDimensionConcreteSaverPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function executePriceDimensionAbstractSaverPlugins(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->executePriceDimensionSaverPlugins($priceProductTransfer, $this->priceDimensionAbstractSaverPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function executePriceDimensionConcreteSaverPlugins(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->executePriceDimensionSaverPlugins($priceProductTransfer, $this->priceDimensionConcreteSaverPlugins);
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
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]|\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[] $priceDimensionSaverPlugins
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function executePriceDimensionSaverPlugins(
        PriceProductTransfer $priceProductTransfer,
        array $priceDimensionSaverPlugins
    ): PriceProductTransfer {
        $priceDimensionType = $priceProductTransfer->getPriceDimension()->getType();

        foreach ($priceDimensionSaverPlugins as $priceDimensionSaverPlugin) {
            if ($priceDimensionSaverPlugin->getDimensionName() !== $priceDimensionType) {
                continue;
            }

            return $priceDimensionSaverPlugin->savePrice($priceProductTransfer);
        }

        return $priceProductTransfer;
    }
}
