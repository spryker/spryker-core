<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryPositionTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStoragePersistenceFactory getFactory()
 */
class CmsBlockCategoryStorageQueryContainer extends AbstractQueryContainer implements CmsBlockCategoryStorageQueryContainerInterface
{
    public const POSITION = 'position';
    public const NAME = 'name';
    protected const KEY = 'key';

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorageQuery
     */
    public function queryCmsBlockCategoryStorageByIds(array $categoryIds)
    {
        return $this->getFactory()
            ->createSpyCmsBlockCategoryStorageQuery()
            ->filterByFkCategory_In($categoryIds);
    }

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategories(array $categoryIds)
    {
        $query = $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->innerJoinCmsBlockCategoryPosition()
            ->innerJoinCmsBlock()
            ->addJoin(
                [SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY, SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY_TEMPLATE],
                [SpyCategoryTableMap::COL_ID_CATEGORY, SpyCategoryTableMap::COL_FK_CATEGORY_TEMPLATE],
                Criteria::INNER_JOIN
            )
            ->withColumn(SpyCmsBlockCategoryPositionTableMap::COL_NAME, static::POSITION)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME);

        if (defined(SpyCmsBlockTableMap::COL_KEY)) {
            $query->withColumn(SpyCmsBlockTableMap::COL_KEY, static::KEY);
        }

        return $query->filterByFkCategory_In($categoryIds);
    }

    /**
     * @api
     *
     * @deprecated Use `\Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainer::queryCmsBlockCategoriesByCmsCategoryIds()` instead.
     *
     * @param int[] $cmsBlockCategoriesIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoriesByIds(array $cmsBlockCategoriesIds): SpyCmsBlockCategoryConnectorQuery
    {
        return $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->innerJoinCmsBlockCategoryPosition()
            ->innerJoinCmsBlock()
            ->addJoin(
                [SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY, SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY_TEMPLATE],
                [SpyCategoryTableMap::COL_ID_CATEGORY, SpyCategoryTableMap::COL_FK_CATEGORY_TEMPLATE],
                Criteria::INNER_JOIN
            )
            ->withColumn(SpyCmsBlockCategoryPositionTableMap::COL_NAME, static::POSITION)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME)
            ->filterByIdCmsBlockCategoryConnector_In($cmsBlockCategoriesIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $cmsBlockCategoriesIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoriesByCmsCategoryIds(array $cmsBlockCategoriesIds): SpyCmsBlockCategoryConnectorQuery
    {
        return $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->filterByIdCmsBlockCategoryConnector_In($cmsBlockCategoriesIds);
    }

    /**
     * @api
     *
     * @param array $idPositions
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCategoryIdsByPositionIds(array $idPositions)
    {
        return $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->filterByFkCmsBlockCategoryPosition_In($idPositions)
            ->select([SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY]);
    }
}
