<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;

class ProductConfigurationPriceExpander implements ProductConfigurationPriceExpanderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expandPriceProductsWithProductConfigurationPrices(
        array $priceProductTransfers,
        ProductViewTransfer $productViewTransfer
    ): array {
        if (!$productViewTransfer->getProductConfigurationInstance()) {
            return $priceProductTransfers;
        }

        if (!$productViewTransfer->getProductConfigurationInstanceOrFail()->getPrices()->count()) {
            return $priceProductTransfers;
        }

        return array_merge(
            $priceProductTransfers,
            $productViewTransfer->getProductConfigurationInstanceOrFail()->getPrices()->getArrayCopy(),
        );
    }
}
