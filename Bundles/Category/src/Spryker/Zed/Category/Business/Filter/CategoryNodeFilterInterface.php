<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer;

interface CategoryNodeFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>>
     */
    public function filterCategoryNodesByValidity(
        CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
    ): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $validCategoryNodeTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $notValidCategoryNodeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>
     */
    public function mergeCategoryNodes(
        ArrayObject $validCategoryNodeTransfers,
        ArrayObject $notValidCategoryNodeTransfers
    ): ArrayObject;
}
