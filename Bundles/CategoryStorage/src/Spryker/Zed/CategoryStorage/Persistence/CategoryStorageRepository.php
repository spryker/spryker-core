<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStoragePersistenceFactory getFactory()
 */
class CategoryStorageRepository extends AbstractRepository implements CategoryStorageRepositoryInterface
{
    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function getCategoryNodesByCategoryNodeIds(array $categoryNodeIds): array
    {
        $categoryNodeEntities = $this->getFactory()
            ->getCategoryNodeQuery()
            ->leftJoinClosureTable(SpyCategoryClosureTableTableMap::TABLE_NAME)
            ->addJoinCondition(
                SpyCategoryClosureTableTableMap::TABLE_NAME,
                SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT . ' = ' . SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                null,
                Criteria::LOGICAL_OR
            )
            ->leftJoinWithSpyUrl()
            ->leftJoinWithCategory()
            ->useCategoryQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithCategoryTemplate()
                ->leftJoinWithAttribute()
                ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithLocale()
                ->endUse()
                ->leftJoinSpyCategoryStore()
                ->useSpyCategoryStoreQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithSpyStore()
                ->endUse()
            ->endUse()
            ->where(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT . ' IN (' . implode(', ', $categoryNodeIds) . ')')
            ->_or()
            ->where(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE . ' IN (' . implode(', ', $categoryNodeIds) . ')')
            ->distinct()
            ->find()
            ->toKeyIndex();

        if ($categoryNodeEntities === []) {
            return [];
        }

        return $this->getFactory()
            ->createCategoryNodeMapper()
            ->mapCategoryNodeEntitiesToNodeTransfersIndexedByIdCategoryNode($categoryNodeEntities, []);
    }
}
