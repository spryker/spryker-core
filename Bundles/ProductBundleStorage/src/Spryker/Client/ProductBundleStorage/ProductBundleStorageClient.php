<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductBundleStorage\ProductBundleStorageFactory getFactory()
 */
class ProductBundleStorageClient extends AbstractClient implements ProductBundleStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithBundledProducts(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer
    {
        return $this->getFactory()
            ->createBundledProductExpander()
            ->expandProductViewWithBundledProducts($productViewTransfer, $localeName);
    }
}
