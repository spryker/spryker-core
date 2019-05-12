<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Generated\Shared\Transfer\CategoryImageStorageItemTransfer;

interface CategoryImageStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer
     *
     * @return void
     */
    public function saveCategoryImageStorage(CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer);

    /**
     * @param int $idCategoryImageStorage
     *
     * @return void
     */
    public function deleteCategoryImageStorage(int $idCategoryImageStorage);
}
