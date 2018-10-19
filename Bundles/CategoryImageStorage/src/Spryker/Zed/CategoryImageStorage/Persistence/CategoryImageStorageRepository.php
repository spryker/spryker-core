<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence;

use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStoragePersistenceFactory getFactory()
 */
class CategoryImageStorageRepository extends AbstractRepository
{
    public const FK_CATEGORY = 'fkCategory';

    /**
     * @api
     *
     * @param array $categoryImageSetToCategoryImageIds
     *
     * @return mixed|\Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryIdsByCategoryImageSetToCategoryImageIds(array $categoryImageSetToCategoryImageIds)
    {
        return $this->getFactory()
            ->createQueryCategoryImageSetToCategoryImage()
            ->filterByIdCategoryImageSetToCategoryImage_In($categoryImageSetToCategoryImageIds)
            ->innerJoinSpyCategoryImageSet()
            ->withColumn('DISTINCT ' . SpyCategoryImageSetTableMap::COL_FK_CATEGORY, static::FK_CATEGORY)
            ->select([static::FK_CATEGORY])
            ->addAnd(SpyCategoryImageSetTableMap::COL_FK_CATEGORY, null, ModelCriteria::NOT_EQUAL)
            ->find();
    }

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryAttributesByIds(array $categoryIds)
    {
        return $this->getFactory()
            ->createCategoryAttributeQuery()
            ->joinWithLocale()
            ->filterByFkCategory_In($categoryIds)
            ->find();
    }

    /**
     * @api
     *
     * @param array $categoryFks
     *
     * @return mixed|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryImageSetsByFkCategoryIn(array $categoryFks)
    {
        $categoryImageSetsQuery = $this->getFactory()
            ->createCategoryImageSetQuery()
            ->innerJoinWithSpyLocale()
            ->innerJoinWithSpyCategoryImageSetToCategoryImage()
            ->useSpyCategoryImageSetToCategoryImageQuery()
            ->innerJoinWithSpyCategoryImage()
            ->endUse()
            ->filterByFkCategory_In($categoryFks);

        return $this->buildQueryFromCriteria($categoryImageSetsQuery)->find();
    }

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryImageStorageByIds(array $categoryIds)
    {
        return $this
            ->getFactory()
            ->createSpyCategoryImageStorageQuery()
            ->filterByFkCategory_In($categoryIds)->find();
    }

    /**
     * @param array $categoryImageIds
     *
     * @return mixed|\Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findCategoryIdsByCategoryImageIds(array $categoryImageIds)
    {
        return $this->getFactory()->createQueryCategoryImageSetToCategoryImage()
            ->filterByFkCategoryImage_In($categoryImageIds)
            ->innerJoinSpyCategoryImageSet()
            ->withColumn('DISTINCT ' . SpyCategoryImageSetTableMap::COL_FK_CATEGORY, static::FK_CATEGORY)
            ->select([static::FK_CATEGORY])
            ->addAnd(SpyCategoryImageSetTableMap::COL_FK_CATEGORY, null, ModelCriteria::NOT_EQUAL)
            ->find();
    }
}
