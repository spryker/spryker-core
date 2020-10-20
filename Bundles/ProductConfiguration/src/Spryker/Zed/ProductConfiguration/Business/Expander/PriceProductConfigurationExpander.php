<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;

class PriceProductConfigurationExpander implements PriceProductConfigurationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function expandPriceProductTransfersWithProductConfigurationPrices(
        array $priceProductTransfers,
        CartChangeTransfer $cartChangeTransfer
    ): array {
        $productConfigurationPriceProductTransfers = [];

        foreach ($cartChangeTransfer->getItems() as $item) {
            $productConfigurationInstance = $item->getProductConfigurationInstance();

            if ($productConfigurationInstance && $productConfigurationInstance->getPrices()->count()) {
                $productConfigurationPriceProductTransfers[] = $productConfigurationInstance->getPrices()->getArrayCopy();
            }
        }

        return array_merge($priceProductTransfers, ...$productConfigurationPriceProductTransfers);
    }
}
