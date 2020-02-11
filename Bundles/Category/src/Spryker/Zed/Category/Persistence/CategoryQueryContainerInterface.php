<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CategoryQueryContainerInterface extends QueryContainerInterface
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
    public function queryNodeWithDirectParent($idLocale);

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeById($idNode);

    /**
     * Specification:
     * - Finds all category-node entities sorted by node order
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoryNodes();

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAllCategoryAttributes();

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildren($idNode);

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
    public function queryNodeByIdCategoryAndParentNode($idCategory, $idParentNode);

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryRootNodes();

    /**
     * @api
     *
     * @param int $idNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildrenByIdLocale($idNode, $idLocale);

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableByNodeId($idNode);

    /**
     * @api
     *
     * @param int $idNode
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableParentEntries($idNode);

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableFilterByIdNode($idNode);

    /**
     * @api
     *
     * @param int $idNodeDescendant
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableFilterByIdNodeDescendant($idNodeDescendant);

    /**
     * @api
     *
     * @param int $idNode
     * @param int $idLocale
     * @param bool $onlyOneLevel
     * @param bool $excludeStartNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryChildren($idNode, $idLocale, $onlyOneLevel = true, $excludeStartNode = true);

    /**
     * @api
     *
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idParentNode
     * @param bool $excludeRoot
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function getChildrenPath($idParentNode, $excludeRoot = true);

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
    public function getParentPath($idChildNode, $idLocale, $excludeRoot = true);

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryRootNode();

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryDescendant($idNode);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryId($idCategory);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllNodesByCategoryId($idCategory);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryMainNodesByCategoryId($idCategory);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNotMainNodesByCategoryId($idCategory);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryById($idCategory);

    /**
     * @api
     *
     * @param string $categoryKey
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryByKey($categoryKey);

    /**
     * @api
     *
     * @param string $categoryKey
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryMainCategoryNodeByCategoryKey($categoryKey);

    /**
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeByNodeId($idNode);

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale);

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
    public function queryAttributeByCategoryIdAndLocale($idCategory, $idLocale);

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $name
     * @param int $idLocale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryAttributesByName($name, $idLocale);

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNode($idLocale);

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
    public function joinCategoryQueryWithChildrenCategories(ModelCriteria $expandableQuery, $rightTableAlias = 'categoryChildren', $fieldIdentifier = 'child', $leftTableAlias = SpyCategoryNodeTableMap::TABLE_NAME);

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
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinCategoryQueryWithParentCategories(ModelCriteria $expandableQuery, $excludeDirectParent = true, $excludeRoot = true, $leftTableAlias = SpyCategoryNodeTableMap::TABLE_NAME, $relationTableAlias = 'categoryParents', $fieldIdentifier = 'parent');

    /**
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $leftAlias
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinCategoryQueryWithUrls(ModelCriteria $expandableQuery, $leftAlias = SpyCategoryNodeTableMap::TABLE_NAME);

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
    public function joinLocalizedRelatedCategoryQueryWithAttributes(ModelCriteria $expandableQuery, $relationTableAlias, $fieldIdentifier);

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
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
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param string $tableAlias
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function selectCategoryAttributeColumns(ModelCriteria $expandableQuery, $tableAlias = SpyCategoryAttributeTableMap::TABLE_NAME);

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryAttributes($idCategory);

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param string $categoryName
     * @param int $idLocale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryName($categoryName, $idLocale);

    /**
     * @api
     *
     * @param string $categoryKey
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryNodeByCategoryKey($categoryKey);

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
    public function queryByCategoryKey($categoryKey, $idLocale);

    /**
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdCategoryNode($idCategoryNode);

    /**
     * @api
     *
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function getCategoryNodesWithOrder($idParentNode, $idLocale);

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale);

    /**
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeId($idCategoryNode);

    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplate();

    /**
     * @api
     *
     * @param int $idCategoryTemplate
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplateById($idCategoryTemplate);

    /**
     * @api
     *
     * @param string $nameCategoryTemplate
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplateByName($nameCategoryTemplate);

    /**
     * @api
     *
     * @param int $idNode
     * @param string $nodeName
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildrenByName(int $idNode, string $nodeName);
}
