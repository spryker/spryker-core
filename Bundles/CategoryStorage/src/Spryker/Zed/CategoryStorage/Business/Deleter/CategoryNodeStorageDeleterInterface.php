<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Deleter;

interface CategoryNodeStorageDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][] $categoryNodeStorageTransferTreesIndexedByLocaleAndStore
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteMissingCategoryNodeStorage(array $categoryNodeStorageTransferTreesIndexedByLocaleAndStore, array $categoryNodeIds): void;

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollection(array $categoryNodeIds): void;
}
