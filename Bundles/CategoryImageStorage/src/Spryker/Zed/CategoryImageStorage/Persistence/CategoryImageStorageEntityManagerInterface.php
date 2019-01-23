<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer;

interface CategoryImageStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransfer
     *
     * @return void
     */
    public function saveCategoryImageStorage(SpyCategoryImageStorageEntityTransfer $categoryImageStorageEntityTransfer);

    /**
     * @param string $idCategoryImageStorageEntityTransfer
     *
     * @return void
     */
    public function deleteCategoryImageStorage(string $idCategoryImageStorageEntityTransfer);
}
