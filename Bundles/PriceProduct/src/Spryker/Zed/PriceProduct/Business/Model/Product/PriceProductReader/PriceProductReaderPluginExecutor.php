<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductReader;

class PriceProductReaderPluginExecutor implements PriceProductReaderPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductMapperPricesExtractorPluginInterface[]
     */
    protected $extractorPlugins;

    /**
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductMapperPricesExtractorPluginInterface[] $extractorPlugins
     */
    public function __construct(array $extractorPlugins)
    {
        $this->extractorPlugins = $extractorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function executePriceExtractorPlugins(array $priceProductTransfers): array
    {
        foreach ($this->extractorPlugins as $extractorPlugin) {
            $priceProductTransfers = array_merge(
                $priceProductTransfers,
                $extractorPlugin->extractProductPrices($priceProductTransfers)
            );
        }

        return $priceProductTransfers;
    }
}
