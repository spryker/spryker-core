<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductBundleStorage\Persistence;

use Generated\Shared\Transfer\ProductBundleStorageTransfer;

interface ProductBundleStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductBundleStorageTransfer $productBundleStorageTransfer
     *
     * @return void
     */
    public function saveProductBundleStorage(ProductBundleStorageTransfer $productBundleStorageTransfer): void;
}
