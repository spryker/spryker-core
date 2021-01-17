<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;

interface CategoryPageSearchToCategoryFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function getCategoryNodeCollectionByCriteria(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): NodeCollectionTransfer;

    /**
     * @param int[] $categoryIds
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByCategoryIds(array $categoryIds): array;
}
