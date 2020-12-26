<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\CategoryAttribute;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryAttributeCreatorInterface
{
    /**
     * @rturn void
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     */
    public function createCategoryLocalizedAttributes(CategoryTransfer $categoryTransfer): void;
}
