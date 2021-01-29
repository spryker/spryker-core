<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;

interface CategoryEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategory(CategoryTransfer $categoryTransfer): CategoryTransfer;

    /**
     * @param int $idCategory
     * @param int[] $storeIds
     *
     * @return void
     */
    public function createCategoryStoreRelationForStores(int $idCategory, array $storeIds): void;

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function createCategoryNode(NodeTransfer $nodeTransfer): NodeTransfer;

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    public function createCategoryClosureTableRootNode(NodeTransfer $nodeTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    public function createCategoryClosureTableNodes(NodeTransfer $nodeTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    public function createCategoryClosureTableParentEntriesForCategoryNode(NodeTransfer $nodeTransfer): void;

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     *
     * @return void
     */
    public function saveCategoryAttribute(int $idCategory, CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $extraParentNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function saveCategoryExtraParentNode(CategoryTransfer $categoryTransfer, NodeTransfer $extraParentNodeTransfer): NodeTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function updateCategoryNode(NodeTransfer $nodeTransfer): NodeTransfer;

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategory(int $idCategory): void;

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryLocalizedAttributes(int $idCategory): void;

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryNode(int $idCategoryNode): void;

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryClosureTable(int $idCategoryNode): void;

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryStoreRelations(int $idCategory): void;

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryClosureTableParentEntriesForCategoryNode(int $idCategoryNode): void;

    /**
     * @param int $idCategory
     * @param int[] $storeIds
     *
     * @return void
     */
    public function deleteCategoryStoreRelationForStores(int $idCategory, array $storeIds): void;
}
