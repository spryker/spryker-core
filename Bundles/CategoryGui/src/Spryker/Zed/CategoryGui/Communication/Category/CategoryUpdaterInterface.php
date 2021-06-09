<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Category;

use Generated\Shared\Transfer\CategoryResponseTransfer;
use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryResponseTransfer
     */
    public function updateCategory(CategoryTransfer $categoryTransfer): CategoryResponseTransfer;
}
