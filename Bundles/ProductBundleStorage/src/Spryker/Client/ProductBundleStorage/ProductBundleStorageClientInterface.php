<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage;

use Generated\Shared\Transfer\ProductBundleStorageCriteriaTransfer;

interface ProductBundleStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves bundled products from storage by provided criteria.
     * - Returns `ProductBundleStorageTransfer` collection indexed by bundle product concrete id.
     *
     * @api
     *
     * @phpstan-return array<int, \Generated\Shared\Transfer\ProductBundleStorageTransfer>
     *
     * @param \Generated\Shared\Transfer\ProductBundleStorageCriteriaTransfer $productBundleStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer[]
     */
    public function getProductBundles(ProductBundleStorageCriteriaTransfer $productBundleStorageCriteriaTransfer): array;
}
