<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer;

interface CategoryUrlFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer $categoryUrlCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer>>
     */
    public function filterCategoriesByValidity(
        CategoryUrlCollectionResponseTransfer $categoryUrlCollectionResponseTransfer
    ): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $validCategoryTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $notValidCategoryTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer>
     */
    public function mergeCategories(
        ArrayObject $validCategoryTransfers,
        ArrayObject $notValidCategoryTransfers
    ): ArrayObject;
}
