<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Deleter;

use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface;

class CategoryNodePageSearchDeleter implements CategoryNodePageSearchDeleterInterface
{
    /**
     * @var \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface
     */
    protected $categoryPageSearchEntityManager;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchEntityManagerInterface $categoryPageSearchEntityManager
     */
    public function __construct(CategoryPageSearchEntityManagerInterface $categoryPageSearchEntityManager)
    {
        $this->categoryPageSearchEntityManager = $categoryPageSearchEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteMissingCategoryNodePageSearchCollection(NodeCollectionTransfer $nodeCollectionTransfer, array $categoryNodeIds): void
    {
        $existingCategoryNodeIds = $this->getCategoryNodeIdsFromNodeTransfers($nodeCollectionTransfer);
        $categoryNodeIdsToDelete = array_diff($categoryNodeIds, $existingCategoryNodeIds);

        $this->deleteCategoryNodePageSearchCollection($categoryNodeIdsToDelete);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollection(array $categoryNodeIds): void
    {
        $this->categoryPageSearchEntityManager->deleteCategoryNodePageSearchByCategoryNodeIds($categoryNodeIds);
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return int[]
     */
    protected function getCategoryNodeIdsFromNodeTransfers(NodeCollectionTransfer $nodeCollectionTransfer): array
    {
        return array_map(function (NodeTransfer $nodeTransfer): int {
            return $nodeTransfer->getIdCategoryNode();
        }, $nodeCollectionTransfer->getNodes()->getArrayCopy());
    }
}
