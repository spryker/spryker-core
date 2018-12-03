<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

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
    public function getCategoryImagesSetsByCategoryId(int $idCategory): array;

    /**
     * Specification:
     * - Persists all provided image sets to database for the given category.
     * - Returns CategoryTransfer along with the data from the persisted CategoryImageSetTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategoryImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer;

    /**
     * Specification:
     * - Persists all provided image sets to database for the given category.
     * - Returns CategoryTransfer along with the data from the persisted CategoryImageSetTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function updateCategoryImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer;

    /**
     * Specification:
     * - Expands the CategoryTransfer with the category's image sets from database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer;
}
