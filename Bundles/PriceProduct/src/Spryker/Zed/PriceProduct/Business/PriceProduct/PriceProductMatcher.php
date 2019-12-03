<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\PriceProduct;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductMatcher implements PriceProductMatcherInterface
{
    /**
     * @var \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductMatcherStrategyPluginInterface[]
     */
    protected $priceProductMatcherStrategyPlugins;

    /**
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductMatcherStrategyPluginInterface[] $priceProductMatcherStrategyPlugins
     */
    public function __construct(array $priceProductMatcherStrategyPlugins)
    {
        $this->priceProductMatcherStrategyPlugins = $priceProductMatcherStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isProductPrice(PriceProductTransfer $priceProductTransfer, ItemTransfer $itemTransfer): bool
    {
        foreach ($this->priceProductMatcherStrategyPlugins as $priceProductMatcherStrategyPlugin) {
            if ($priceProductMatcherStrategyPlugin->isApplicable($priceProductTransfer, $itemTransfer)) {
                return $priceProductMatcherStrategyPlugin->isProductPrice($priceProductTransfer, $itemTransfer);
            }
        }

        return $priceProductTransfer->getSkuProduct() === $itemTransfer->getSku();
    }
}
