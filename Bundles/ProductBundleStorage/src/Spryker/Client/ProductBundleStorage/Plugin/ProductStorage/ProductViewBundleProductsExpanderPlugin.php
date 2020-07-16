<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface getClient()
 * @method \Spryker\Client\ProductBundleStorage\ProductBundleStorageFactory getFactory()
 */
class ProductViewBundleProductsExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductView transfer object with bundled products.
     *
     * @api
     *
     * @phpstan-param array<mixed> $productData
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName
    ): ProductViewTransfer {
        return $this->getClient()->expandProductViewWithBundledProducts($productViewTransfer, $localeName);
    }
}
