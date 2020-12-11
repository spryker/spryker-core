<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface ProductCategoryFilterGuiToCategoryFacadeInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToCategoryFacadeInterface::findCategory()} instead.
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $localeTransfer);

    /**
     * @param int $idCategory
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory);

    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategory(CategoryCriteriaTransfer $categoryCriteriaTransfer): ?CategoryTransfer;
}
