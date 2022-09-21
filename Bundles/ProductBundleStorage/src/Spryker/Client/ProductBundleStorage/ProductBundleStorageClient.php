<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage;

use Generated\Shared\Transfer\ProductBundleStorageCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\ProductBundleStorageCriteriaTransfer $productBundleStorageCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductBundleStorageTransfer>
     */
    public function getProductBundles(ProductBundleStorageCriteriaTransfer $productBundleStorageCriteriaTransfer): array
    {
        return $this->getFactory()
            ->createProductBundleStorageReader()
            ->getProductBundles($productBundleStorageCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithBundledProducts(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        string $localeName
    ): ProductViewTransfer {
        return $this->getFactory()->createProductViewProductBundleExpander()->expandProductViewTransfer(
            $productViewTransfer,
            $productData,
            $localeName,
        );
    }
}
