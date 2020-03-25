<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStoragePersistenceFactory getFactory()
 */
class CategoryStorageRepository extends AbstractRepository implements CategoryStorageRepositoryInterface
{
    /**
     * @param int $categoryNodeId
     *
     * @return int
     */
    public function getParentCategoryNodeIdByCategoryNodeId(int $categoryNodeId): int
    {
        return $this->getFactory()
            ->createSpyCategoryNodeQuery()
            ->filterByIdCategoryNode($categoryNodeId)
            ->select(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE)
            ->find()
            ->getFirst();
    }

    /**
     * @param int $categoryNodeId
     *
     * @return int[]
     */
    public function getCategoryNodeIdsByParentCategoryNodeId(int $categoryNodeId): array
    {
        return $this->getFactory()
            ->createSpyCategoryNodeQuery()
            ->filterByFkParentCategoryNode($categoryNodeId)
            ->select(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE)
            ->find()
            ->getData();
    }
}
