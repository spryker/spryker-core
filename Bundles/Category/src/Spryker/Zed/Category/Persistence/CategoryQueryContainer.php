<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryClosureTableTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryPersistenceFactory getFactory()
 */
class CategoryQueryContainer extends AbstractQueryContainer implements CategoryQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeById($idNode)
    {
        return $this->getFactory()->createCategoryNodeQuery()->filterByIdCategoryNode($idNode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoryNodes()
    {
        return $this->getFactory()
            ->createCategoryNodeQuery()
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAllCategoryAttributes()
    {
        return $this->getFactory()->createCategoryAttributeQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildren($idNode)
    {
        return $this->getFactory()->createCategoryNodeQuery()
            ->filterByFkParentCategoryNode($idNode)
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryRootNodes()
    {
        return $this->getFactory()->createCategoryAttributeQuery()
            ->joinLocale()
            ->addJoin(
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                Criteria::INNER_JOIN
            )
            ->addAnd(
                SpyCategoryNodeTableMap::COL_IS_ROOT,
                true,
                Criteria::EQUAL
            )
            ->withColumn(
                SpyLocaleTableMap::COL_LOCALE_NAME,
                'locale_name'
            )
            ->withColumn(
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                'id_category_node'
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableByNodeId($idNode)
    {
        $query = $this->getFactory()->createCategoryClosureTableQuery();

        return $query->where(
            SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE . ' = ?',
            $idNode
        )->_or()->where(
            SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT . ' = ?',
            $idNode
        );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableParentEntries($idNode)
    {
        $query = $this->getFactory()->createCategoryClosureTableQuery();
        $query->setModelAlias('node');

        $joinCategoryNodeDescendant = new Join(
            'node.fk_category_node_descendant',
            'descendants.fk_category_node_descendant',
            Criteria::JOIN
        );
        $joinCategoryNodeDescendant
            ->setRightTableName('spy_category_closure_table')
            ->setRightTableAlias('descendants')
            ->setLeftTableName('spy_category_closure_table')
            ->setLeftTableAlias('node');

        $joinCategoryNodeAscendant = new Join(
            'descendants.fk_category_node',
            'ascendants.fk_category_node',
            Criteria::LEFT_JOIN
        );

        $joinCategoryNodeAscendant
            ->setRightTableName('spy_category_closure_table')
            ->setRightTableAlias('ascendants')
            ->setLeftTableName('spy_category_closure_table')
            ->setLeftTableAlias('descendants');

        $query->addJoinObject($joinCategoryNodeDescendant);
        $query->addJoinObject($joinCategoryNodeAscendant, 'ascendantsJoin');

        $query->addJoinCondition(
            'ascendantsJoin',
            'ascendants.fk_category_node_descendant = node.fk_category_node'
        );

        $query
            ->where(
                'descendants.fk_category_node = ' . $idNode
            )
            ->where(
                'ascendants.fk_category_node IS NULL'
            );

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableFilterByIdNode($idNode)
    {
        return $this->getFactory()->createCategoryClosureTableQuery()
            ->filterByFkCategoryNode($idNode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idNodeDescendant
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableFilterByIdNodeDescendant($idNodeDescendant)
    {
        return $this->getFactory()->createCategoryClosureTableQuery()
            ->filterByFkCategoryNodeDescendant($idNodeDescendant);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idNode
     * @param int $idLocale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryPath($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false)
    {
        $depth = null;

        if ($onlyParents) {
            $depth = 0;
        }

        $nodeQuery = $this->getFactory()->createCategoryNodeQuery();

        if ($excludeRootNode) {
            $nodeQuery->filterByIsRoot(false);
        }

        $nodeQuery
            ->useClosureTableQuery()
            ->orderByFkCategoryNodeDescendant(Criteria::DESC)
            ->orderByDepth(Criteria::DESC)
            ->filterByFkCategoryNodeDescendant($idNode)
            ->filterByDepth($depth, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
            ->useAttributeQuery()
            ->filterByFkLocale($idLocale)
            ->endUse()
            ->endUse();

        $nodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, 'fk_category')
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->withColumn(SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT, 'fk_category_node_descendant')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, 'category_key')
            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_LOCALE, 'fk_locale');

        $nodeQuery->setFormatter(new PropelArraySetFormatter());

        return $nodeQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryRootNode()
    {
        return $this->getFactory()->createCategoryNodeQuery()
            ->filterByIsRoot(true)
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryId($idCategory)
    {
        return $this->getFactory()->createCategoryAttributeQuery()->filterByFkCategory($idCategory);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllNodesByCategoryId($idCategory)
    {
        return $this->queryNodesByCategoryId($idCategory, null);
    }

    /**
     * @param int $idCategory
     * @param mixed $isMain true|false|null
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function queryNodesByCategoryId($idCategory, $isMain)
    {
        $query = $this->getFactory()->createCategoryNodeQuery()
            ->filterByFkCategory($idCategory);

        if ($isMain !== null) {
            $query->filterByIsMain($isMain);
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryById($idCategory)
    {
        return $this->getFactory()->createCategoryQuery()->filterByIdCategory($idCategory);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale)
    {
        return $this->getFactory()->createCategoryQuery()
            ->joinAttribute()
            ->innerJoinNode()
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $idLocale,
                Criteria::EQUAL
            )
            ->withColumn(SpyCategoryTableMap::COL_ID_CATEGORY, 'id_category')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, 'category_key')
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node');
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNode($idLocale)
    {
        $nodeQuery = $this->getFactory()->createCategoryNodeQuery();
        $nodeQuery->useCategoryQuery()
            ->useAttributeQuery()
            ->filterByFkLocale($idLocale)
            ->endUse()
            ->endUse();
        $nodeQuery
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'category_name');

        return $nodeQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdCategoryNode($idCategoryNode)
    {
        return $this->getFactory()->createUrlQuery()
            ->joinSpyLocale()
            ->filterByFkResourceCategorynode($idCategoryNode)
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeId($idCategoryNode)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByFkResourceCategorynode($idCategoryNode);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplate()
    {
        return $this->getFactory()
            ->createCategoryTemplateQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $nameCategoryTemplate
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplateByName($nameCategoryTemplate)
    {
        return $this->queryCategoryTemplate()
            ->filterByName($nameCategoryTemplate);
    }
}
