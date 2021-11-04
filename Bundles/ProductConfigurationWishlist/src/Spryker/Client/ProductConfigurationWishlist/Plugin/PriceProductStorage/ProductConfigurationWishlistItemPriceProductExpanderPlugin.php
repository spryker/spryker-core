<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist\Plugin\PriceProductStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductConfigurationWishlist\ProductConfigurationWishlistClientInterface getClient()
 */
class ProductConfigurationWishlistItemPriceProductExpanderPlugin extends AbstractPlugin implements PriceProductExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands collection of product price transfers with product configuration prices taken from `ProductViewTransfer`.
     * - Expects `ProductViewTransfer::productConfigurationInstance::prices` to be provided.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expand(array $priceProductTransfers, ProductViewTransfer $productViewTransfer): array
    {
        return $this->getClient()
            ->expandPriceProductsWithProductConfigurationPrices($priceProductTransfers, $productViewTransfer);
    }
}
