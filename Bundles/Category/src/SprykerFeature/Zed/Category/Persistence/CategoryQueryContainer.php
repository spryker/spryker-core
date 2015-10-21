<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Persistence;

use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryClosureTableTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Propel\Business\Formatter\PropelArraySetFormatter;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Url\Persistence\Propel\Map\SpyUrlTableMap;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

class CategoryQueryContainer extends AbstractQueryContainer implements CategoryQueryContainerInterface
{

    /**
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeWithDirectParent($idLocale)
    {
        $query = SpyCategoryNodeQuery::create()
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
     * @param int $idNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeById($idNode)
    {
        return SpyCategoryNodeQuery::create()->filterByIdCategoryNode($idNode);
    }

    /**
     * @param int $idNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildren($idNode)
    {
        return SpyCategoryNodeQuery::create()
            ->filterByFkParentCategoryNode($idNode)
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);
    }

    /**
     * @param int $idCategory
     * @param int $idParentNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeByIdCategoryAndParentNode($idCategory, $idParentNode)
    {
        return SpyCategoryNodeQuery::create()
            ->filterByFkParentCategoryNode($idParentNode)
            ->where(
                SpyCategoryNodeTableMap::COL_FK_CATEGORY . ' = ?',
                $idCategory
            )
        ;
    }

    /**
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryRootNodes($idLocale)
    {
        return SpyCategoryAttributeQuery::create()
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
     * @param int $idNode
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildrenByIdLocale($idNode, $idLocale)
    {
        $nodeQuery = SpyCategoryNodeQuery::create()
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
     * @param int $idNode
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function queryClosureTableByNodeId($idNode)
    {
        $query = SpyCategoryClosureTableQuery::create();

        return $query->where(
            SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE . ' = ?',
            $idNode
        )->_or()->where(
            SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT . ' = ?',
            $idNode
        );
    }

    /**
     * @param int $idNode
     *
     * @throws PropelException
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function queryClosureTableParentEntries($idNode)
    {
        $query = SpyCategoryClosureTableQuery::create('node');

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
     * @param int $idNode
     *
     * @return $this|SpyCategoryClosureTableQuery
     */
    public function queryClosureTableFilterByIdNode($idNode)
    {
        return SpyCategoryClosureTableQuery::create()
            ->filterByFkCategoryNode($idNode);
    }

    /**
     * @param int $idNodeDescendant
     *
     * @return $this|SpyCategoryClosureTableQuery
     */
    public function queryClosureTableFilterByIdNodeDescendant($idNodeDescendant)
    {
        return SpyCategoryClosureTableQuery::create()
            ->filterByFkCategoryNodeDescendant($idNodeDescendant);
    }

    /**
     * Order of columns matters when using addSelectColumn()
     *
     * TODO https://spryker.atlassian.net/browse/CD-563
     *
     * @param int $idNode
     * @param string $idLocale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryChildren($idNode, $idLocale, $onlyOneLevel = true, $excludeStartNode = true)
    {
        $nodeQuery = SpyCategoryNodeQuery::create();
        $nodeQuery
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse()
            ->useDescendantQuery()
                ->filterByFkCategoryNode($idNode)
            ->endUse()
            ->addSelectColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE)
            ->addSelectColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE)
            ->addSelectColumn(SpyCategoryAttributeTableMap::COL_NAME);

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
     * @param int $idNode
     * @param int $idLocale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @throws PropelException
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryPath($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false)
    {
        $depth = null;

        if ($onlyParents) {
            $depth = 0;
        }

        $nodeQuery = SpyCategoryNodeQuery::create();
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

        if ($excludeRootNode) {
            $nodeQuery->filterByIsRoot(0);
        }

        $nodeQuery
            ->addAsColumn('fk_category', SpyCategoryNodeTableMap::COL_FK_CATEGORY)
            ->addAsColumn('id_category_node', SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE)
            ->addAsColumn('fk_category_node_descendant', SpyCategoryClosureTableTableMap::COL_FK_CATEGORY_NODE_DESCENDANT)
            ->addAsColumn('name', SpyCategoryAttributeTableMap::COL_NAME);

        $nodeQuery->setFormatter(new PropelArraySetFormatter());

        return $nodeQuery;
    }

    /**
     * @param int $idParentNode
     * @param bool $excludeRoot
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function getChildrenPath($idParentNode, $excludeRoot = true)
    {
        $query = new SpyCategoryClosureTableQuery();
        $query->filterByFkCategoryNode($idParentNode)
            ->innerJoinNode()
            ->where(SpyCategoryClosureTableTableMap::COL_DEPTH . '> ?', 0);

        if ($excludeRoot) {
            $query->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false');
        }

        return $query;
    }

    /**
     * @param int $idChildNode
     * @param bool $excludeRoot
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function getParentPath($idChildNode, $excludeRoot = true)
    {
        $query = new SpyCategoryClosureTableQuery();
        $query->filterByFkCategoryNodeDescendant($idChildNode)
            ->innerJoinNode()
            ->useNodeQuery()
            ->innerJoinCategory()
            ->useCategoryQuery()
            ->innerJoinAttribute()
            ->endUse()
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryAttributeTableMap::COL_URL_KEY, 'url_key')
            ->orderBy(SpyCategoryClosureTableTableMap::COL_DEPTH, 'DESC');

        if ($excludeRoot) {
            $query->where(SpyCategoryNodeTableMap::COL_IS_ROOT . ' = false');
        }

        return $query;
    }

    /**
     * @return SpyCategoryNodeQuery
     */
    public function queryRootNode()
    {
        return SpyCategoryNodeQuery::create()
            ->filterByIsRoot(true)
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);
    }

    /**
     * @param int $idNode
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function queryDescendant($idNode)
    {
        return SpyCategoryClosureTableQuery::create()->filterByFkCategoryNode($idNode);
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryId($idCategory)
    {
        return SpyCategoryAttributeQuery::create()->filterByFkCategory($idCategory);
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryAllNodesByCategoryId($idCategory)
    {
        return $this->queryNodesByCategoryId($idCategory, null);
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryMainNodesByCategoryId($idCategory)
    {
        return $this->queryNodesByCategoryId($idCategory, true);
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNotMainNodesByCategoryId($idCategory)
    {
        return $this->queryNodesByCategoryId($idCategory, false);
    }

    /**
     * @param $idCategory
     * @param mixed $isMain true|false|null
     *
     * @return SpyCategoryNodeQuery
     */
    protected function queryNodesByCategoryId($idCategory, $isMain)
    {
        $query = SpyCategoryNodeQuery::create()
            ->filterByFkCategory($idCategory);

        if (null !== $isMain) {
            $query->filterByIsMain($isMain);
        }

        return $query;
    }

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryQuery
     */
    public function queryCategoryById($idCategory)
    {
        return SpyCategoryQuery::create()->filterByIdCategory($idCategory);
    }

    /**
     * @param $idNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryCategoryNodeByNodeId($idNode)
    {
        return SpyCategoryNodeQuery::create()->filterByIdCategoryNode($idNode);
    }

    /**
     * @param int $idLocale
     *
     * @return SpyCategoryQuery
     */
    public function queryCategory($idLocale)
    {
        return SpyCategoryQuery::create()
            ->joinAttribute()
            ->innerJoinNode()
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $idLocale,
                Criteria::EQUAL
            )
            ->withColumn(SpyCategoryTableMap::COL_ID_CATEGORY, 'id_category')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node');
    }

    /**
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryIdAndLocale($idCategory, $idLocale)
    {
        return SpyCategoryAttributeQuery::create()
            ->joinLocale()
            ->filterByFkLocale($idLocale)
            ->filterByFkCategory($idCategory)
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME);
    }

    /**
     * @param string $name
     * @param int $idLocale
     *
     * @throws PropelException
     *
     * @return SpyCategoryAttributeQuery
     */
    public function queryCategoryAttributesByName($name, $idLocale)
    {
        return SpyCategoryAttributeQuery::create()
            ->filterByName($name)
            ->filterByFkLocale($idLocale);
    }

    /**
     * @param int $idLocale
     *
     * @throws PropelException
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryCategoryNode($idLocale)
    {
        $nodeQuery = SpyCategoryNodeQuery::create();
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
     * @param ModelCriteria $expandableQuery
     * @param string $rightTableAlias
     * @param string $fieldIdentifier
     * @param string $leftTableAlias
     *
     * @return ModelCriteria
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
     * @param ModelCriteria $expandableQuery
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     * @param string $leftTableAlias
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @throws PropelException
     *
     * @return ModelCriteria
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
     * @param ModelCriteria $expandableQuery
     * @param string $leftAlias
     *
     * @return ModelCriteria
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
     * @param ModelCriteria $expandableQuery
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @return ModelCriteria
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
     * @param ModelCriteria $expandableQuery
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @throws PropelException
     *
     * @return ModelCriteria
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
     * @param ModelCriteria $expandableQuery
     * @param string $tableAlias
     *
     * @return ModelCriteria
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
     * @param string $categoryName
     * @param int $idLocale
     *
     * @throws PropelException
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryName($categoryName, $idLocale)
    {
        $nodeQuery = SpyCategoryNodeQuery::create();
        $nodeQuery->useCategoryQuery()
            ->useAttributeQuery()
            ->filterByName($categoryName)
            ->filterByFkLocale($idLocale)
            ->endUse()
            ->endUse();

        return $nodeQuery;
    }

    /**
     * @param int $idCategoryNode
     *
     * @return SpyUrlQuery
     */
    public function queryUrlByIdCategoryNode($idCategoryNode)
    {
        return SpyUrlQuery::create()
            ->joinSpyLocale()
            ->filterByFkResourceCategorynode($idCategoryNode)
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME);
    }

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale)
    {
        return SpyCategoryNodeQuery::create()
            ->filterByFkParentCategoryNode($idParentNode)
            ->useCategoryQuery()
            ->innerJoinAttribute()
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $idLocale,
                Criteria::EQUAL
            )
            ->endUse()
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC);
    }

}
