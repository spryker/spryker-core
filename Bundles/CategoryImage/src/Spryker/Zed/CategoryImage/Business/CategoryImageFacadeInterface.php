<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;

/**
 * @method \Spryker\Zed\CategoryImage\Business\CategoryImageBusinessFactory getFactory()
 */
interface CategoryImageFacadeInterface
{
    /**
     * Specification:
     * - Returns all category image sets from database for the given category id.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCategoryImageSetsByIdCategory(int $idCategory): array;

    /**
     * Specification:
     * - Persists all provided image sets to database for the given category.
     * - Returns the collection of transfers for persisted image sets.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function createCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): ArrayObject;

    /**
     * Specification:
     * - Updates image sets for the given category.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): void;

    /**
     * Specification:
     * - Deletes all the category image sets for the specified category id.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryImageSetsByIdCategory(int $idCategory): void;

    /**
     * Specification:
     * - Expands the CategoryTransfer with the category's image sets from database.
     * - Returns expanded CategoryTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer;
}
