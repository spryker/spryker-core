<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Deleter;

interface CategoryNodePageSearchDeleterInterface
{
    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function deleteCategoryNodePageSearchCollection(array $categoryNodeIds): void;
}
