<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\ImageSet;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;

interface ImageSetCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryImageSetTransfer>
     */
    public function createCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): ArrayObject;
}
