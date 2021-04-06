<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiPersistenceFactory getFactory()
 */
class CategoryGuiRepository extends AbstractRepository implements CategoryGuiRepositoryInterface
{
    /**
     * @module Category
     * @module Store
     *
     * @param int[] $categoryIds
     *
     * @return string[][]
     */
    public function getCategoryStoreNamesGroupedByCategoryId(array $categoryIds): array
    {
        $categoryStoreNames = $this->getFactory()->getCategoryPropelQuery()
            ->filterByIdCategory_In($categoryIds)
            ->leftJoinWithSpyCategoryStore()
            ->useSpyCategoryStoreQuery()
                ->leftJoinWithSpyStore()
            ->endUse()
            ->select([
                SpyStoreTableMap::COL_NAME,
                SpyCategoryTableMap::COL_ID_CATEGORY,
            ])->find()->toArray();

        $categoryStoreNamesGroupedByCategoryId = [];
        foreach ($categoryStoreNames as $categoryStoreName) {
            $CategoryId = $categoryStoreName[SpyCategoryTableMap::COL_ID_CATEGORY];
            $categoryStoreNamesGroupedByCategoryId[$CategoryId][] = $categoryStoreName[SpyStoreTableMap::COL_NAME];
        }

        return $categoryStoreNamesGroupedByCategoryId;
    }
}
