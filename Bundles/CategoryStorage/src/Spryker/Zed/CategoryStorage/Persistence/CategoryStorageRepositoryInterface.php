<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

interface CategoryStorageRepositoryInterface
{
    /**
     * @param int $categoryNodeId
     *
     * @return int|null
     */
    public function findParentCategoryNodeIdByCategoryNodeId(int $categoryNodeId): ?int;

    /**
     * @param int $categoryNodeId
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByParentCategoryNodeId(int $categoryNodeId): array;
}
