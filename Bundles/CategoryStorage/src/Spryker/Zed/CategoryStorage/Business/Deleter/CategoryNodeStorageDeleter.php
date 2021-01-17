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
     * /**
     *
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageEntityManagerInterface $categoryStorageEntityManager
     */
    public function __construct(CategoryStorageEntityManagerInterface $categoryStorageEntityManager)
    {
        $this->categoryStorageEntityManager = $categoryStorageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteMissingCategoryNodeStorage(array $nodeTransfers, array $categoryNodeIds): void
    {
        $existingCategoryNodeIds = $this->getCategoryNodeIdsFromNodeTransfers($nodeTransfers);
        $categoryNodeIdsToDelete = array_diff($categoryNodeIds, $existingCategoryNodeIds);

        $this->deleteCollection($categoryNodeIdsToDelete);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCollection(array $categoryNodeIds): void
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
            return $nodeTransfer->getIdCategoryNode();
        }, $nodeTransfers);
    }
}
