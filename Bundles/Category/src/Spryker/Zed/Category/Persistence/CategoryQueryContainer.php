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
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryPersistenceFactory getFactory()
 */
class CategoryQueryContainer extends AbstractQueryContainer implements CategoryQueryContainerInterface
{
    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeWithDirectParent($idLocale)
    {
        $query = $this->getFactory()->createCategoryNodeQuery()
            ->addJoin(
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                Criteria::INNER_JOIN
            );
        $query->addJoinObject(
            (
            new Join(
                SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE,
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                Criteria::LEFT_JOIN
            )
            )->setRightTableAlias('parent'),
            'parentJoin'
        );
        $query->addJoinObject(
            (
            new Join(
                'parent.fk_category',
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                Criteria::LEFT_JOIN
            )
            )->setRightTableAlias('parentAttributes'),
            'parentAttributesJoin'
        );
        $query->addAnd(
            SpyCategoryAttributeTableMap::COL_FK_LOCALE,
            $idLocale,
            Criteria::EQUAL
        );
        $query->addCond(
            'parentAttributesJoin',
            SpyCategoryAttributeTableMap::COL_FK_LOCALE . '=' .
            $idLocale
        );
        $query->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'category_name')
            ->withColumn('parentAttributes.name', 'parent_category_name');

        return $query;
    }

    /**
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
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoryNodes()
    {
        return $this->getFactory()->createCategoryNodeQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAllCategoryAttributes()
    {
        return $this->getFactory()->createCategoryAttributeQuery();
    }

    /**
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     * @param int $idParentNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByIdCategoryAndParentNode($idCategory, $idParentNode)
    {
        return $this->getFactory()->createCategoryNodeQuery()
            ->filterByFkParentCategoryNode($idParentNode)
            ->where(
                SpyCategoryNodeTableMap::COL_FK_CATEGORY . ' = ?',
                $idCategory
            );
    }

    /**
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
     * @api
     *
     * @param int $idNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildrenByIdLocale($idNode, $idLocale)
    {
        $nodeQuery = $this->getFactory()->createCategoryNodeQuery()
            ->joinParentCategoryNode('parentNode')
            ->addJoin(
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                Criteria::INNER_JOIN
            )
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $idLocale,
                Criteria::EQUAL
            )
            ->addAnd(
                SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE,
                $idNode,
                Criteria::EQUAL
            )
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);

        return $nodeQuery;
    }

    /**
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
     * @api
     *
     * @param int $idNode
     * @param string $idLocale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryChildren($idNode, $idLocale, $onlyOneLevel = true, $excludeStartNode = true)
    {
        $nodeQuery = $this->getFactory()->createCategoryNodeQuery();
        $nodeQuery
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse()
            ->useDescendantQuery()
                ->filterByFkCategoryNode($idNode)
            ->endUse()
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE)
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME)
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY);

        if ($excludeStartNode) {
            $nodeQuery->filterByIdCategoryNode($idNode, Criteria::NOT_EQUAL);
        }

        if ($onlyOneLevel) {
            $nodeQuery->filterByIdCategoryNode($idNode)
                ->_or();
            $nodeQuery->filterByFkParentCategoryNode($idNode);
        }

        $nodeQuery->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);

        return $nodeQuery;
    }

    /**
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
            $nodeQuery->filterByIsRoot(0);
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idParentNode
     * @param bool $excludeRoot
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function getChildrenPath($idParentNode, $excludeRoot = true)
    {
        $query = $this->getFactory()->createCategoryClosureTableQuery();
        $query->filterByFkCategoryNode($idParentNode)
            ->innerJoinNode()
            ->where(SpyCategoryClosureTableTableMap::COL_DEPTH . '> ?', 0);

        if ($excludeRoot) {
            $query->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false');
        }

        return $query;
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idChildNode
     * @param int $idLocale
     * @param bool $excludeRoot
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function getParentPath($idChildNode, $idLocale, $excludeRoot = true)
    {
        $query = $this->getFactory()->createCategoryClosureTableQuery();
        $query->filterByFkCategoryNodeDescendant($idChildNode)
            ->innerJoinNode()
            ->useNodeQuery()
                ->innerJoinCategory()
                ->useCategoryQuery()
                    ->innerJoinAttribute()
                    ->addAnd(SpyCategoryAttributeTableMap::COL_FK_LOCALE, $idLocale)
                ->endUse()
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryTableMap::COL_CATEGORY_KEY, 'category_key')
            ->orderBy(SpyCategoryClosureTableTableMap::COL_DEPTH, 'DESC');

        if ($excludeRoot) {
            $query->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false');
        }

        return $query;
    }

    /**
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryDescendant($idNode)
    {
        return $this->getFactory()->createCategoryClosureTableQuery()->filterByFkCategoryNode($idNode);
    }

    /**
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
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryMainNodesByCategoryId($idCategory)
    {
        return $this->queryNodesByCategoryId($idCategory, true);
    }

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNotMainNodesByCategoryId($idCategory)
    {
        return $this->queryNodesByCategoryId($idCategory, false);
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
     * @api
     *
     * @param string $categoryKey
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryByKey($categoryKey)
    {
        return $this->getFactory()->createCategoryQuery()->filterByCategoryKey($categoryKey);
    }

    /**
     * @api
     *
     * @param string $categoryKey
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryMainCategoryNodeByCategoryKey($categoryKey)
    {
        return $this->getFactory()->createCategoryNodeQuery()
            ->filterByIsMain(true)
            ->useCategoryQuery()
                ->filterByCategoryKey($categoryKey)
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeByNodeId($idNode)
    {
        return $this->getFactory()->createCategoryNodeQuery()->filterByIdCategoryNode($idNode);
    }

    /**
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryIdAndLocale($idCategory, $idLocale)
    {
        return $this->getFactory()->createCategoryAttributeQuery()
            ->joinLocale()
            ->filterByFkLocale($idLocale)
            ->filterByFkCategory($idCategory)
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $name
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryAttributesByName($name, $idLocale)
    {
        return $this->getFactory()->createCategoryAttributeQuery()
            ->filterByName($name)
            ->filterByFkLocale($idLocale);
    }

    /**
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $rightTableAlias
     * @param string $fieldIdentifier
     * @param string $leftTableAlias
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinCategoryQueryWithChildrenCategories(
        ModelCriteria $expandableQuery,
        $rightTableAlias = 'categoryChildren',
        $fieldIdentifier = 'child',
        $leftTableAlias = SpyCategoryNodeTableMap::TABLE_NAME
    ) {
        $expandableQuery
            ->addJoinObject(
                (new Join(
                    $leftTableAlias . '.id_category_node',
                    SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE,
                    Criteria::LEFT_JOIN
                ))->setRightTableAlias($rightTableAlias)
            );

        $expandableQuery->withColumn(
            'GROUP_CONCAT(' . $rightTableAlias . '.id_category_node)',
            'category_' . $fieldIdentifier . '_ids'
        );

        return $expandableQuery;
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     * @param string $leftTableAlias
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinCategoryQueryWithParentCategories(
        ModelCriteria $expandableQuery,
        $excludeDirectParent = true,
        $excludeRoot = true,
        $leftTableAlias = SpyCategoryNodeTableMap::TABLE_NAME,
        $relationTableAlias = 'categoryParents',
        $fieldIdentifier = 'parent'
    ) {
        $expandableQuery
            ->addJoinObject(
                (new Join(
                    $leftTableAlias . '.id_category_node',
                    SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT,
                    Criteria::LEFT_JOIN
                ))
            );

        $expandableQuery
            ->addJoinObject(
                (new Join(
                    SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE,
                    SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                    Criteria::INNER_JOIN
                ))->setRightTableAlias($relationTableAlias),
                $relationTableAlias . 'Join'
            );

        if ($excludeDirectParent) {
            $expandableQuery->addAnd(
                SpyCategoryClosureTableTableMap::COL_DEPTH,
                0,
                Criteria::GREATER_THAN
            );
        }

        if ($excludeRoot) {
            $expandableQuery->addJoinCondition(
                $relationTableAlias . 'Join',
                $relationTableAlias . '.is_root = false'
            );
        }

        $expandableQuery->withColumn(
            'GROUP_CONCAT(' . $relationTableAlias . '.id_category_node)',
            'category_' . $fieldIdentifier . '_ids'
        );
        $expandableQuery->withColumn(
            SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT,
            'descendant_id'
        );
        $expandableQuery->withColumn(
            SpyCategoryClosureTableTableMap::COL_DEPTH,
            'depth'
        );

        return $expandableQuery;
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $leftAlias
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinCategoryQueryWithUrls(
        ModelCriteria $expandableQuery,
        $leftAlias = SpyCategoryNodeTableMap::TABLE_NAME
    ) {
        $expandableQuery
            ->addJoinObject(
                (new Join(
                    $leftAlias . '.id_category_node',
                    SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE,
                    Criteria::LEFT_JOIN
                ))->setRightTableAlias('categoryUrls'),
                'categoryUrlJoin'
            );

        $expandableQuery->addJoinCondition(
            'categoryUrlJoin',
            'categoryUrls.fk_locale = ' .
            SpyLocaleTableMap::COL_ID_LOCALE
        );

        $expandableQuery->withColumn(
            'GROUP_CONCAT(categoryUrls.url)',
            'category_urls'
        );

        return $expandableQuery;
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinLocalizedRelatedCategoryQueryWithAttributes(
        ModelCriteria $expandableQuery,
        $relationTableAlias,
        $fieldIdentifier
    ) {
        $expandableQuery->addJoinObject(
            (new Join(
                $relationTableAlias . '.fk_category',
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                Criteria::LEFT_JOIN
            ))->setRightTableAlias($relationTableAlias . 'Attributes'),
            $relationTableAlias . 'AttributesJoin'
        );

        $expandableQuery->addCond(
            $relationTableAlias . 'AttributesJoin',
            SpyCategoryAttributeTableMap::COL_FK_LOCALE . '=' .
            SpyLocaleTableMap::COL_ID_LOCALE
        );

        $expandableQuery->withColumn(
            'GROUP_CONCAT(' . $relationTableAlias . 'Attributes.name)',
            'category_' . $fieldIdentifier . '_names'
        );

        return $expandableQuery;
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinRelatedCategoryQueryWithUrls(
        ModelCriteria $expandableQuery,
        $relationTableAlias,
        $fieldIdentifier
    ) {
        $expandableQuery->addJoinObject(
            (new Join(
                $relationTableAlias . '.id_category_node',
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE,
                Criteria::LEFT_JOIN
            ))->setRightTableAlias($relationTableAlias . 'Urls'),
            $relationTableAlias . 'UrlJoin'
        );

        $expandableQuery->addJoinCondition(
            $relationTableAlias . 'UrlJoin',
            $relationTableAlias . 'Urls.fk_locale = ' .
            SpyLocaleTableMap::COL_ID_LOCALE
        );

        $expandableQuery->withColumn(
            'GROUP_CONCAT(' . $relationTableAlias . 'Urls.url)',
            'category_' . $fieldIdentifier . '_urls'
        );

        return $expandableQuery;
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $tableAlias
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function selectCategoryAttributeColumns(
        ModelCriteria $expandableQuery,
        $tableAlias = SpyCategoryAttributeTableMap::TABLE_NAME
    ) {
        $expandableQuery->withColumn(
            $tableAlias . '.name',
            'category_name'
        );
        $expandableQuery->withColumn(
            $tableAlias . '.meta_title',
            'category_meta_title'
        );
        $expandableQuery->withColumn(
            $tableAlias . '.meta_description',
            'category_meta_description'
        );
        $expandableQuery->withColumn(
            $tableAlias . '.meta_keywords',
            'category_meta_keywords'
        );
        $expandableQuery->withColumn(
            $tableAlias . '.category_image_name',
            'category_image_name'
        );

        return $expandableQuery;
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryAttributes($idCategory)
    {
        return $this->getFactory()->createCategoryAttributeQuery()
            ->filterByFkCategory($idCategory);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryName($categoryName, $idLocale)
    {
        $nodeQuery = $this->getFactory()->createCategoryNodeQuery();
        $nodeQuery
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByName($categoryName)
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse();

        return $nodeQuery;
    }

    /**
     * @api
     *
     * @param string $categoryKey
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryKey($categoryKey)
    {
        $nodeQuery = $this->getFactory()->createCategoryNodeQuery();
        $nodeQuery->useCategoryQuery()
            ->filterByCategoryKey($categoryKey)
            ->endUse();

        return $nodeQuery;
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryKey
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryByCategoryKey($categoryKey, $idLocale)
    {
        $query = $this->getFactory()->createCategoryQuery();
        $query
            ->filterByCategoryKey($categoryKey)
            ->useAttributeQuery()
                ->joinLocale()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name');

        return $query;
    }

    /**
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
     * @api
     *
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale)
    {
        return $this->getFactory()->createCategoryNodeQuery()
            ->filterByFkParentCategoryNode($idParentNode)
            ->useCategoryQuery()
                ->innerJoinAttribute()
                ->addAnd(
                    SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                    $idLocale,
                    Criteria::EQUAL
                )
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME)
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByFkResourceCategorynode($idCategoryNode);
        $query->filterByFkLocale($idLocale);

        return $query;
    }

    /**
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
     * @api
     *
     * @param int $idCategoryTemplate
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplateById($idCategoryTemplate)
    {
        return $this->queryCategoryTemplate()
            ->filterByIdCategoryTemplate($idCategoryTemplate);
    }

    /**
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

    /**
     * @api
     *
     * @param int $idNode
     * @param string $nodeName
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildrenByName(int $idNode, string $nodeName)
    {
        return $this->getFactory()->createCategoryNodeQuery()
            ->filterByFkParentCategoryNode($idNode)
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC)
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByName($nodeName)
                ->endUse()
            ->endUse();
    }
}
