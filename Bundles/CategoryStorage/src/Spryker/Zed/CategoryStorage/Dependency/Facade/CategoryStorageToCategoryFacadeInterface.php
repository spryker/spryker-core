<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Dependency\Facade;

interface CategoryStorageToCategoryFacadeInterface
{
    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getAllCategoryNodeTreeElementsByCategoryNodeIds(array $categoryNodeIds): array;

    /**
     * @param int[] $categoryStoreIds
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByCategoryIds(array $categoryStoreIds): array;
}
