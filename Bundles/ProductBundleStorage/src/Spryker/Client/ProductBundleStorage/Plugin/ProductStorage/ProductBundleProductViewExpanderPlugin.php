<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductBundleStorage\ProductBundleStorageFactory getFactory()
 * @method \Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface getClient()
 */
class ProductBundleProductViewExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `ProductViewTransfer.idProductConcrete` to be set.
     * - Checks if `ProductViewTransfer.idProductConcrete` is specified, otherwise skips the extension.
     * - Reads bundled products from the storage.
     * - Reads store specific ProductConcrete resources from Storage.
     * - Expands `ProductViewTransfer.bundledProducts` with `ProductForProductBundleStorageTransfer` objects.
     * - Returns an expanded `ProductViewTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<string, mixed> $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName
    ): ProductViewTransfer {
        return $this->getClient()->expandProductViewTransferWithBundledProducts($productViewTransfer, $productData, $localeName);
    }
}
