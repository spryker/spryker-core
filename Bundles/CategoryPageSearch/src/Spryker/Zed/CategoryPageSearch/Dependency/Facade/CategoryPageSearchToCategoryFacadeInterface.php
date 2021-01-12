<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\NodeCollectionTransfer;

interface CategoryPageSearchToCategoryFacadeInterface
{
    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getActiveCategoryNodesByCategoryNodeIds(array $categoryNodeIds): NodeCollectionTransfer;

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByCategoryIds(array $categoryIds): array;
}
