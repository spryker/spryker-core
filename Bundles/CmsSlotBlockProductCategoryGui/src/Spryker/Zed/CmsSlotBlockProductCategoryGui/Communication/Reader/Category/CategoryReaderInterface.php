<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category;

interface CategoryReaderInterface
{
    /**
     * @return int[]
     */
    public function getCategories(): array;
}
