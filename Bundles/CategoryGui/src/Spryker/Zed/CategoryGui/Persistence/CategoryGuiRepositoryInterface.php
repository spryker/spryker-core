<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Generated\Shared\Transfer\CategoryStoreNameCollectionTransfer;

interface CategoryGuiRepositoryInterface
{
    /**
     * @module Category
     *
     * @param int[] $categoryIds
     *
     * @return \Generated\Shared\Transfer\CategoryStoreNameCollectionTransfer
     */
    public function getCategoryStoreNamesGroupedByCategoryId(array $categoryIds): CategoryStoreNameCollectionTransfer;
}
