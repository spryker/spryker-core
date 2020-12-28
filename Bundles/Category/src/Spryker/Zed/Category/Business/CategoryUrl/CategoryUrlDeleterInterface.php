<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\CategoryUrl;

interface CategoryUrlDeleterInterface
{
    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryUrlsForCategory(int $idCategory): void;

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryUrlsForCategoryNode(int $idCategoryNode): void;
}
