<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Handler;

use Generated\Shared\Transfer\CategoryResponseTransfer;

interface CategoryDeleteFormHandlerInterface
{
    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryResponseTransfer
     */
    public function deleteCategory(int $idCategory): CategoryResponseTransfer;
}
