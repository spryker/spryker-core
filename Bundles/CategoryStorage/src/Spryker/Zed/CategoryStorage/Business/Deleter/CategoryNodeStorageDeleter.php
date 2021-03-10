<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Deleter;

use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface;

class CategoryNodeStorageDeleter implements CategoryNodeStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface
     */
    protected $categoryStorageEntityManager;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     */
    public function __construct(CategoryStorageEntityManagerInterface $categoryStorageEntityManager)
    {
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[][][] $categoryNodeStorageTransferTreesIndexedByLocaleAndStore
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteMissingCategoryNodeStorage(array $categoryNodeStorageTransferTreesIndexedByLocaleAndStore, array $categoryNodeIds): void
    {
        foreach ($categoryNodeStorageTransferTreesIndexedByLocaleAndStore as $storeName => $categoryNodeStorageTransferTreesIndexedByLocale) {
            foreach ($categoryNodeStorageTransferTreesIndexedByLocale as $localeName => $categoryNodeStorageTransfers) {
                $this->deleteMissingCategoryNodeStorageForLocaleAndStore($categoryNodeIds, $categoryNodeStorageTransfers, $localeName, $storeName);
            }
        }
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodeStorageCollection(array $categoryNodeIds): void
    {
        $this->categoryStorageEntityManager->deleteCategoryNodeStorageByCategoryNodeIds($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     *
     * @return int[]
     */
    protected function getCategoryNodeIdsFromNodeTransfers(array $nodeTransfers): array
    {
        return array_map(function (NodeTransfer $nodeTransfer): int {
            return $nodeTransfer->getIdCategoryNodeOrFail();
        }, $nodeTransfers);
    }

    /**
     * @param int[] $categoryNodeIds
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     * @param string $localeName
     * @param string $storeName
     *
     * @return void
     */
    protected function deleteMissingCategoryNodeStorageForLocaleAndStore(
        array $categoryNodeIds,
        array $categoryNodeStorageTransfers,
        string $localeName,
        string $storeName
    ): void {
        $categoryNodeIdsToDelete = $categoryNodeIds;
        if ($categoryNodeStorageTransfers !== []) {
            $categoryNodeIdsToDelete = array_diff($categoryNodeIds, array_keys($categoryNodeStorageTransfers));
        }

        $this->categoryStorageEntityManager->deleteCategoryNodeStoragesForStoreAndLocale($categoryNodeIdsToDelete, $localeName, $storeName);
    }
}
