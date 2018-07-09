<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapper;

use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductMapperPluginExecutor implements PriceProductMapperPluginExecutorInterface
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function executePriceExtractorPlugins(PriceProductTransfer $priceProductTransfer): array
    {
        $priceProducts = [];

        foreach ($this->extractorPlugins as $extractorPlugin) {
            $priceProducts = array_merge(
                $priceProducts,
                $extractorPlugin->extractProductPrices($priceProductTransfer)
            );
        }

        return $priceProducts;
    }
}