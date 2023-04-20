<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer;

/**
 * Use this plugin to expand `ProductAbstractCategoryStorageCollectionTransfer` with additional data after categories retrieval from Storage.
 */
interface ProductAbstractCategoryStorageCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `ProductAbstractCategoryStorageCollectionTransfer` with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer $productAbstractCategoryStorageCollectionTransfer
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageCollectionTransfer
     */
    public function expand(
        ProductAbstractCategoryStorageCollectionTransfer $productAbstractCategoryStorageCollectionTransfer,
        string $localeName,
        string $storeName
    ): ProductAbstractCategoryStorageCollectionTransfer;
}
