<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;

interface CategoryQueryContainerInterface
{

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeWithDirectParent($idLocale);

    /**
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeById($idNode);

    /**
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildren($idNode);

    /**
     * @param int $idCategory
     * @param int $idParentNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByIdCategoryAndParentNode($idCategory, $idParentNode);

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryRootNodes();

    /**
     * @param int $idNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildrenByIdLocale($idNode, $idLocale);

    /**
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableByNodeId($idNode);

    /**
     * @param int $idNode
     * @param string $idLocale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryChildren($idNode, $idLocale, $onlyOneLevel = true, $excludeStartNode = true);

    /**
     * @param int $idNode
     * @param int $idLocale
     * @param bool $excludeRootNode
     * @param bool $onlyParents
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryPath($idNode, $idLocale, $excludeRootNode = true, $onlyParents = false);

    /**
     * @param int $idParentNode
     * @param bool $excludeRoot
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function getChildrenPath($idParentNode, $excludeRoot = true);

    /**
     * @param int $idChildNode
     * @param int $idLocale
     * @param bool $excludeRoot
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function getParentPath($idChildNode, $idLocale, $excludeRoot = true);

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryRootNode();

    /**
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryDescendant($idNode);

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryId($idCategory);

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllNodesByCategoryId($idCategory);

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryMainNodesByCategoryId($idCategory);

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNotMainNodesByCategoryId($idCategory);

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryById($idCategory);

    /**
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeByNodeId($idNode);

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale);

    /**
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryIdAndLocale($idCategory, $idLocale);

    /**
     * @param string $name
     * @param int $idLocale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryAttributesByName($name, $idLocale);

    /**
     * @param int $idLocale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNode($idLocale);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $rightTableAlias
     * @param string $fieldIdentifier
     * @param string $leftTableAlias
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinCategoryQueryWithChildrenCategories(ModelCriteria $expandableQuery, $rightTableAlias = 'categoryChildren', $fieldIdentifier = 'child', $leftTableAlias = SpyCategoryNodeTableMap::TABLE_NAME);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     * @param string $leftTableAlias
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinCategoryQueryWithParentCategories(ModelCriteria $expandableQuery, $excludeDirectParent = true, $excludeRoot = true, $leftTableAlias = SpyCategoryNodeTableMap::TABLE_NAME, $relationTableAlias = 'categoryParents', $fieldIdentifier = 'parent');

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $leftAlias
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinCategoryQueryWithUrls(ModelCriteria $expandableQuery, $leftAlias = SpyCategoryNodeTableMap::TABLE_NAME);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinLocalizedRelatedCategoryQueryWithAttributes(ModelCriteria $expandableQuery, $relationTableAlias, $fieldIdentifier);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $relationTableAlias
     * @param string $fieldIdentifier
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinRelatedCategoryQueryWithUrls(ModelCriteria $expandableQuery, $relationTableAlias, $fieldIdentifier);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $tableAlias
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function selectCategoryAttributeColumns(ModelCriteria $expandableQuery, $tableAlias = SpyCategoryAttributeTableMap::TABLE_NAME);

    /**
     * @param string $categoryName
     * @param int $idLocale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryName($categoryName, $idLocale);

    /**
     * @param string $categoryKey
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryKey($categoryKey);

    /**
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdCategoryNode($idCategoryNode);

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale);

}
