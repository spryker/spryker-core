<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\Category;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function hydrateCategoryCollection(CategoryCollectionTransfer $categoryCollectionTransfer, LocaleTransfer $localeTransfer): void;
}
