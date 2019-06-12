<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageStorageItemTransfer;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetTableMap;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
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
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
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
        $categoryImageSetEntities = $categoryImageSetsQuery->find();

        return $this->mapCategoryImageSetEntityToCategoryImageSetTransfer($categoryImageSetEntities);
    }

    /**
     * @param array $categoryIds
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemTransfer[]
     */
    public function getCategoryImageStorageByFkCategoryIn(array $categoryIds): array
    {
        $categoryImageStorageEntities = $this->getFactory()
            ->createSpyCategoryImageStorageQuery()
            ->filterByFkCategory_In($categoryIds)
            ->find();

        return $this->mapCategoryImageStorageEntityToCategoryImageStorageItemTransfer($categoryImageStorageEntities);
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

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $categoryImageSetEntities
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    protected function mapCategoryImageSetEntityToCategoryImageSetTransfer(ObjectCollection $categoryImageSetEntities): array
    {
        $categoryImageSetTransfers = [];
        $categoryImageStorageMapper = $this->getFactory()->createCategoryImageStorageMapper();

        foreach ($categoryImageSetEntities as $categoryImageSetEntity) {
            $categoryImageSetTransfers[] = $categoryImageStorageMapper->mapCategoryImageSetEntityToCategoryImageSetTransfer(
                $categoryImageSetEntity,
                new CategoryImageSetTransfer()
            );
        }

        return $categoryImageSetTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $categoryImageStorageEntities
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemTransfer[]
     */
    protected function mapCategoryImageStorageEntityToCategoryImageStorageItemTransfer(ObjectCollection $categoryImageStorageEntities): array
    {
        $categoryImageStorageItemTransfers = [];
        $categoryImageStorageMapper = $this->getFactory()->createCategoryImageStorageMapper();

        foreach ($categoryImageStorageEntities as $categoryImageStorageEntity) {
            $categoryImageStorageItemTransfers[] = $categoryImageStorageMapper->mapCategoryImageStorageEntityToCategoryImageStorageItemTransfer(
                $categoryImageStorageEntity,
                new CategoryImageStorageItemTransfer()
            );
        }

        return $categoryImageStorageItemTransfers;
    }
}
