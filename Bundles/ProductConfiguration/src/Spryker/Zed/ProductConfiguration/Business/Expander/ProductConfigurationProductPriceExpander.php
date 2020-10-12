<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;

class ProductConfigurationProductPriceExpander implements ProductConfigurationProductPriceExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array
     */
    public function expandPriceProductTransfersWithProductConfigurationPrices(
        array $priceProductTransfers,
        CartChangeTransfer $cartChangeTransfer
    ): array {
        $productConfigurationPrices = [];

        foreach ($cartChangeTransfer->getItems() as $item) {
            $productConfigurationInstance = $item->getProductConfigurationInstance();

            if ($productConfigurationInstance && $productConfigurationInstance->getPrices()->count()) {
                $productConfigurationPrices[] = $productConfigurationInstance->getPrices();
            }
        }

        return array_merge($priceProductTransfers, $productConfigurationPrices);
    }
}
