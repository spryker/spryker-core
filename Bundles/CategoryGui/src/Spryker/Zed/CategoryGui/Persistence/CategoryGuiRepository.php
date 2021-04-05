<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Generated\Shared\Transfer\CategoryStoreNameCollectionTransfer;
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
     *
     * @param int[] $categoryIds
     *
     * @return \Generated\Shared\Transfer\CategoryStoreNameCollectionTransfer
     */
    public function getCategoryStoreNamesGroupedByCategoryId(array $categoryIds): CategoryStoreNameCollectionTransfer
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
            $categoryStoreNamesGroupedByCategoryId[$categoryStoreName[SpyCategoryTableMap::COL_ID_CATEGORY]][]
                = $categoryStoreName[SpyStoreTableMap::COL_NAME];
        }

        return (new CategoryStoreNameCollectionTransfer())->setCategoryStoreNames($categoryStoreNamesGroupedByCategoryId);
    }
}
