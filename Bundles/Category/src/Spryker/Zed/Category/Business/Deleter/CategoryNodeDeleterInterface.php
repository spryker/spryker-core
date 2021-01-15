<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Deleter;

interface CategoryNodeDeleterInterface
{
    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryNodes(int $idCategory): void;

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryExtraParentNodes(int $idCategory): void;
}
