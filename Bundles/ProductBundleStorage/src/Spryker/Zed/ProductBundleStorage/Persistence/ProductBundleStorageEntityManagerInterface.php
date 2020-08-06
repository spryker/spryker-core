<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
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
