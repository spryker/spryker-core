<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;

interface ProductCategoryStorageEntityManagerInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractCategoryStorages(array $productAbstractIds): void;

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    public function deleteProductAbstractCategoryStorage(
        int $idProductAbstract,
        string $storeName,
        string $localeName
    ): void;

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
     *
     * @return void
     */
    public function saveProductAbstractCategoryStorage(
        int $idProductAbstract,
        string $storeName,
        string $localeName,
        ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
    ): void;
}
