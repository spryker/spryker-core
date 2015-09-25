<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Category\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

interface CategoryQueryContainerInterface
{
    /**
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeWithDirectParent($idLocale);

    /**
     * @param int $idNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeById($idNode);

    /**
     * @param int $idNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildren($idNode);

    /**
     * @param $idCategory
     * @param $idParentNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeByIdCategoryAndParentNode($idCategory, $idParentNode);

    /**
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryRootNodes($idLocale);

    /**
     * @param int $idNode
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildrenByIdLocale($idNode, $idLocale);

    /**
     * @param int $idNode
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function queryClosureTableByNodeId($idNode);

    /**
     * @param int $idNode
     * @param string $idLocale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryChildren($idNode, $idLocale, $onlyOneLevel = true, $excludeStartNode = true);

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
    public function queryPath($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @param int $idParentNode
     * @param bool $excludeRoot
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function getChildrenPath($idParentNode, $excludeRoot = true);

    /**
     * @param int $idChildNode
     * @param bool $excludeRoot
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function getParentPath($idChildNode, $excludeRoot = true);

    /**
     * @return SpyCategoryNodeQuery
     */
    public function queryRootNode();

    /**
     * @param int $idNode
     *
     * @return SpyCategoryClosureTableQuery
     */
    public function queryDescendant($idNode);

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryId($idCategory);

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryAllNodesByCategoryId($idCategory);

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryMainNodesByCategoryId($idCategory);

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNotMainNodesByCategoryId($idCategory);

    /**
     * @param int $idCategory
     *
     * @return SpyCategoryQuery
     */
    public function queryCategoryById($idCategory);

    /**
     * @param $idNode
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryCategoryNodeByNodeId($idNode);

    /**
     * @param int $idLocale
     *
     * @return SpyCategoryQuery
     */
    public function queryCategory($idLocale);

    /**
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryIdAndLocale($idCategory, $idLocale);

    /**
     * @param string $name
     * @param int $idLocale
     *
     * @throws PropelException
     *
     * @return SpyCategoryAttributeQuery
     */
    public function queryCategoryAttributesByName($name, $idLocale);

    /**
     * @param int $idLocale
     *
     * @throws PropelException
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryCategoryNode($idLocale);

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $rightTableAlias
     * @param string $fieldIdentifier
     * @param string $leftTableAlias
     *
     * @return ModelCriteria
     */
    public function joinCategoryQueryWithChildrenCategories(ModelCriteria $expandableQuery, $rightTableAlias = 'categoryChildren', $fieldIdentifier = 'child', $leftTableAlias = SpyCategoryNodeTableMap::TABLE_NAME);

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
    public function joinCategoryQueryWithParentCategories(ModelCriteria $expandableQuery, $excludeDirectParent = true, $excludeRoot = true, $leftTableAlias = SpyCategoryNodeTableMap::TABLE_NAME, $relationTableAlias = 'categoryParents', $fieldIdentifier = 'parent');

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $leftAlias
     *
     * @return ModelCriteria
     */
    public function joinCategoryQueryWithUrls(ModelCriteria $expandableQuery, $leftAlias = SpyCategoryNodeTableMap::TABLE_NAME);

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @return ModelCriteria
     */
    public function joinLocalizedRelatedCategoryQueryWithAttributes(ModelCriteria $expandableQuery, $relationTableAlias, $fieldIdentifier);

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @throws PropelException
     *
     * @return ModelCriteria
     */
    public function joinRelatedCategoryQueryWithUrls(ModelCriteria $expandableQuery, $relationTableAlias, $fieldIdentifier);

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $tableAlias
     *
     * @return ModelCriteria
     */
    public function selectCategoryAttributeColumns(ModelCriteria $expandableQuery, $tableAlias = SpyCategoryAttributeTableMap::TABLE_NAME);

    /**
     * @param string $categoryName
     * @param int $idLocale
     *
     * @throws PropelException
     *
     * @return SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryName($categoryName, $idLocale);

    /**
     * @param $idCategoryNode
     *
     * @return SpyUrlQuery
     */
    public function queryUrlByIdCategoryNode($idCategoryNode);

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return SpyCategoryNodeQuery
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale);
}
