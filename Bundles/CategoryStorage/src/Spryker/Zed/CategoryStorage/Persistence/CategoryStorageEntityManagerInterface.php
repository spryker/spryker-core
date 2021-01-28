<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CategoryTreeStorageTransfer;

interface CategoryStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    public function saveCategoryNodeStorageForStoreAndLocale(
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer,
        string $storeName,
        string $localeName
    ): void;

    /**
     * @param int[] $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return void
     */
    public function deleteCategoryNodeStoragesForStoreAndLocale(array $categoryNodeIds, string $localeName, string $storeName): void;

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodeStorageByCategoryNodeIds(array $categoryNodeIds): void;

    /**
     * @param \Generated\Shared\Transfer\CategoryTreeStorageTransfer $categoryTreeStorageTransfer
     *
     * @return void
     */
    public function saveCategoryTreeStorage(CategoryTreeStorageTransfer $categoryTreeStorageTransfer): void;

    /**
     * @return void
     */
    public function deleteCategoryTreeStorageCollection(): void;
}
