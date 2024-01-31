<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer;

interface CategoryClosureTableFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer $categoryClosureTableCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>>
     */
    public function filterCategoryNodesByValidity(
        CategoryClosureTableCollectionResponseTransfer $categoryClosureTableCollectionResponseTransfer
    ): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $validNodeTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $notValidNodeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>
     */
    public function mergeCategoryNodes(
        ArrayObject $validNodeTransfers,
        ArrayObject $notValidNodeTransfers
    ): ArrayObject;
}
