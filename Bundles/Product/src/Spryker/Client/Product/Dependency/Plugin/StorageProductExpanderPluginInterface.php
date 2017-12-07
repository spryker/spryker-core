<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Dependency\Plugin;

use Generated\Shared\Transfer\StorageProductTransfer;

interface StorageProductExpanderPluginInterface
{
    /**
     * Specification:
     * - This method maps raw product data to StorageProductTransfer.
     * - The plugin is called from ProductClient::mapStorageProduct() and ProductClient::mapStorageProductForCurrentLocale() methods.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StorageProductTransfer $storageProductTransfer
     * @param array $productData
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function expandStorageProduct(StorageProductTransfer $storageProductTransfer, array $productData, $locale);
}
