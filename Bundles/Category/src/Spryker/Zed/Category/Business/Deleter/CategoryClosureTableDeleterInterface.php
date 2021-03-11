<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Deleter;

interface CategoryClosureTableDeleterInterface
{
    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryClosureTable(int $idCategoryNode): void;
}
