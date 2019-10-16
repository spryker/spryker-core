<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetTableMap;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStoragePersistenceFactory getFactory()
 */
class CategoryImageStorageRepository extends AbstractRepository implements CategoryImageStorageRepositoryInterface
{
    public const FK_CATEGORY = 'fkCategory';

    /**
     * @param array $categoryImageSetIds
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getCategoryIdsByCategoryImageSetIds(array $categoryImageSetIds)
    {
        return $this->getFactory()
            ->getCategoryImageSetQuery()
            ->filterByIdCategoryImageSet_In($categoryImageSetIds)
            ->select([SpyCategoryImageSetTableMap::COL_FK_CATEGORY])
            ->find();
    }

    /**
     * @param array $categoryIds
     *
     * @return \Generated\Shared\Transfer\SpyCategoryImageSetEntityTransfer[]
     */
    public function getCategoryImageSetsByFkCategoryIn(array $categoryIds): array
    {
        $categoryImageSetsQuery = $this->getFactory()
            ->getCategoryImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyCategoryImageSetToCategoryImage()
            ->useSpyCategoryImageSetToCategoryImageQuery()
                ->innerJoinWithSpyCategoryImage()
            ->endUse()
            ->filterByFkCategory_In($categoryIds);

        $categoryImageSetsQuery = $this->sortCategoryImageSetToCategoryImageQuery($categoryImageSetsQuery);

        return $this->buildQueryFromCriteria($categoryImageSetsQuery)->find();
    }

    /**
     * @param array $categoryIds
     *
     * @return \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer[]
     */
    public function getCategoryImageStorageByFkCategoryIn(array $categoryIds): array
    {
        $categoryImageStorageQuery = $this->getFactory()
            ->createSpyCategoryImageStorageQuery()
            ->filterByFkCategory_In($categoryIds);

        return $this->buildQueryFromCriteria($categoryImageStorageQuery)->find();
    }

    /**
     * @param array $categoryImageIds
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getCategoryIdsByCategoryImageIds(array $categoryImageIds)
    {
        return $this->getFactory()
            ->getQueryCategoryImageSetToCategoryImage()
            ->filterByFkCategoryImage_In($categoryImageIds)
            ->innerJoinSpyCategoryImageSet()
            ->withColumn('DISTINCT ' . SpyCategoryImageSetTableMap::COL_FK_CATEGORY, static::FK_CATEGORY)
            ->select([static::FK_CATEGORY])
            ->addAnd(SpyCategoryImageSetTableMap::COL_FK_CATEGORY, null, ModelCriteria::NOT_EQUAL)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $categoryIds
     *
     * @return \Generated\Shared\Transfer\SpyCategoryImageStorageEntityTransfer[]
     */
    public function getCategoryImageStorageByFilter(FilterTransfer $filterTransfer, array $categoryIds): array
    {
        $categoryImageStorageQuery = $this->getFactory()
            ->createSpyCategoryImageStorageQuery()
            ->filterByFkCategory_In($categoryIds)
            ->limit($filterTransfer->getLimit())
            ->offset($filterTransfer->getOffset());

        return $this->buildQueryFromCriteria($categoryImageStorageQuery)->find();
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery $categoryImageSetToCategoryImageQuery
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery
     */
    protected function sortCategoryImageSetToCategoryImageQuery(
        SpyCategoryImageSetQuery $categoryImageSetToCategoryImageQuery
    ): SpyCategoryImageSetQuery {
        $categoryImageSetToCategoryImageQuery->useSpyCategoryImageSetToCategoryImageQuery()
                ->orderBySortOrder()
                ->orderByIdCategoryImageSetToCategoryImage()
            ->endUse();

        return $categoryImageSetToCategoryImageQuery;
    }
}
