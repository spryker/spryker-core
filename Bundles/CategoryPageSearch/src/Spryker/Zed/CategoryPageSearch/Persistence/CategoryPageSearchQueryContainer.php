<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchPersistenceFactory getFactory()
 */
class CategoryPageSearchQueryContainer extends AbstractQueryContainer implements CategoryPageSearchQueryContainerInterface
{
    public const ID_CATEGORY_NODE = 'idCategoryNode';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $localeNames
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocalesWithLocaleNames(array $localeNames)
    {
        return $this->getFactory()
            ->getLocaleQueryContainer()
            ->queryLocales()
            ->filterByLocaleName_In($localeNames);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryNodeIds
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeTree(array $categoryNodeIds, $idLocale)
    {
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery $query */
        $query = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByIdCategoryNode_In($categoryNodeIds)
            ->joinWithSpyUrl()
            ->joinWithCategory()
            ->joinWith('Category.Attribute')
            ->joinWith('Category.CategoryTemplate')
            ->where(SpyCategoryAttributeTableMap::COL_FK_LOCALE . ' = ?', $idLocale)
            ->where(SpyUrlTableMap::COL_FK_LOCALE . ' = ?', $idLocale)
            ->where(SpyCategoryTableMap::COL_IS_ACTIVE . ' = ?', true)
            ->where(SpyCategoryTableMap::COL_IS_IN_MENU . ' = ?', true);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @module Url
     * @module Category
     *
     * @param int[] $categoryNodeIds
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryWholeCategoryNodeTree(array $categoryNodeIds, int $idLocale): SpyCategoryNodeQuery
    {
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery $categoryNodeQuery */
        $categoryNodeQuery = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByIdCategoryNode_In($categoryNodeIds)
            ->joinWithSpyUrl()
            ->useSpyUrlQuery(null, Criteria::INNER_JOIN)
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->joinWithCategory()
            ->useCategoryQuery()
                ->joinWithAttribute()
                ->useAttributeQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
                ->filterByIsActive(true)
                ->joinWithCategoryTemplate()
            ->endUse();

        return $categoryNodeQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryRoot()
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByIsRoot(true);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeIdsByCategoryIds(array $categoryIds)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByFkCategory_In($categoryIds)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::ID_CATEGORY_NODE)
            ->select([static::ID_CATEGORY_NODE]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearchQuery
     */
    public function queryCategoryNodePageSearchByIds(array $categoryNodeIds)
    {
        return $this->getFactory()
            ->createSpyCategoryNodePageSearchQuery()
            ->filterByFkCategoryNode_In($categoryNodeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryTemplateIds
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeIdsByTemplateIds(array $categoryTemplateIds)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->useCategoryQuery()
            ->filterByFkCategoryTemplate_In($categoryTemplateIds)
            ->endUse()
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::ID_CATEGORY_NODE)
            ->select([static::ID_CATEGORY_NODE]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodesByIds(array $ids): SpyCategoryNodeQuery
    {
        $query = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByIdCategoryNode_In($ids);

        return $query;
    }
}
