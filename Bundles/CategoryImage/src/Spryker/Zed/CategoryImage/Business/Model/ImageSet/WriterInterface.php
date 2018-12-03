<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model\ImageSet;

use Generated\Shared\Transfer\CategoryTransfer;

interface WriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): CategoryTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function updateCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): CategoryTransfer;

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryImageSetsByIdCategory(int $idCategory): void;
}
