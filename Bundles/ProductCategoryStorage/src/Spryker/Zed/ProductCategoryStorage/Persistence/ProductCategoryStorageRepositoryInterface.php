<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

interface ProductCategoryStorageRepositoryInterface
{
    /**
     * @return array
     */
    public function getAllCategoriesOrderedByDescendant(): array;

    /**
     * @return int[]
     */
    public function getAllCategoryNodeIds(): array;
}
