<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImagePersistenceFactory getFactory()
 */
interface CategoryImageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function saveCategoryImage(CategoryImageTransfer $categoryImageTransfer): CategoryImageTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function saveCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSet
     *
     * @return void
     */
    public function deleteCategoryImageSet(CategoryImageSetTransfer $categoryImageSet): void;

    /**
     * @param int $idCategoryImageSet
     * @param int $idCategoryImage
     * @param int|null $sortOrder
     *
     * @return int
     */
    public function saveCategoryImageSetToCategoryImage(int $idCategoryImageSet, int $idCategoryImage, $sortOrder = null): int;

    /**
     * @param int $idCategoryImageSet
     * @param int $idCategoryImage
     *
     * @return void
     */
    public function deleteCategoryImageSetToCategoryImage(int $idCategoryImageSet, int $idCategoryImage): void;
}
