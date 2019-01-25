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
     * @param int $idCategoryImageSet
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function saveCategoryImage(
        CategoryImageTransfer $categoryImageTransfer,
        int $idCategoryImageSet
    ): CategoryImageTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function saveCategoryImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryImageSetTransfer;

    /**
     * @param int $idCategoryImageSet
     *
     * @return void
     */
    public function deleteCategoryImageSetById(int $idCategoryImageSet): void;

    /**
     * @param int $idCategoryImage
     * @param int $idCategoryImageSet
     *
     * @return void
     */
    public function deleteCategoryImageFromImageSetById(int $idCategoryImage, int $idCategoryImageSet): void;
}
