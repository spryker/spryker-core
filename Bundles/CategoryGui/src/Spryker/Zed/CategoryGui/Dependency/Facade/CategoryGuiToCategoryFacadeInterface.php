<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Dependency\Facade;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryGuiToCategoryFacadeInterface
{
    /**
     * @param string $name
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function checkSameLevelCategoryByNameExists(string $name, CategoryTransfer $categoryTransfer): bool;

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer;

    /**
     * @return void
     */
    public function syncCategoryTemplate();

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer);

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllNodesByIdCategory(int $idCategory);

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete(int $idCategory);

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer);

    /**
     * @param int $idCategoryNode
     * @param int $position
     *
     * @return void
     */
    public function updateCategoryNodeOrder(int $idCategoryNode, int $position);

    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategory(CategoryCriteriaTransfer $categoryCriteriaTransfer): ?CategoryTransfer;
}
