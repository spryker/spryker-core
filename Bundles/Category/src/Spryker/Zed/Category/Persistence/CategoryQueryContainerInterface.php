<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CategoryQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - Filters category node query on the `id_category_node` column.
     *
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
     * Specification:
     * - Creates category attribute query.
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAllCategoryAttributes();

    /**
     * Specification:
     * - Filters category node query on the `fk_parent_category_node` column.
     * - Finds category node entities sorted by node order.
     *
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryFirstLevelChildren($idNode);

    /**
     * Specification:
     * - Creates category attribute query for root nodes with virtual columns.
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryRootNodes();

    /**
     * Specification:
     * - Filters category closure table query on the `fk_category_node` or `fk_category_node_descendant` column.
     *
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableByNodeId($idNode);

    /**
     * Specification:
     * - Filters category closure table query on the `fk_category_node` column with additional joins.
     *
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
     * Specification:
     * - Filters category closure table query on the `fk_category_node` column.
     *
     * @api
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableFilterByIdNode($idNode);

    /**
     * Specification:
     * - Filters category closure table query on the `fk_category_node_descendant` column.
     *
     * @api
     *
     * @param int $idNodeDescendant
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryClosureTableFilterByIdNodeDescendant($idNodeDescendant);

    /**
     * Specification:
     * - Creates category node query with virtual columns.
     * - Filters category node query on the `fk_category_node_descendant` and `fk_locale` columns.
     *
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
     * Specification:
     * - Creates category node query.
     * - Filter the query on the `is_root` column is true.
     * - Finds category node entities sorted by node order.
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryRootNode();

    /**
     * Specification:
     * - Creates category attribute query.
     * - Filter query on the `fk_category` column.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryAttributeByCategoryId($idCategory);

    /**
     * Specification:
     * - Creates category node query.
     * - Filter query on the `fk_category` column.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllNodesByCategoryId($idCategory);

    /**
     * Specification:
     * - Creates category query.
     * - Filter query on the `id_category` column.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryById($idCategory);

    /**
     * Specification:
     * - Creates category query with virtual columns.
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale);

    /**
     * Specification:
     * - Creates category node query with virtual columns.
     * - Filter query on the `fk_locale` column.
     *
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
     * Specification:
     * - Creates url query.
     * - Filter query on the `fk_resource_categorynode` column.
     *
     * @api
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeId($idCategoryNode);

    /**
     * Specification:
     * - Creates category template query.
     *
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplate();

    /**
     * Specification:
     * - Creates category template query.
     * - Filter query on the `name` column.
     *
     * @api
     *
     * @param string $nameCategoryTemplate
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplateByName($nameCategoryTemplate);

    /**
     * Specification:
     * - Creates category store query.
     * - Filter query on the `fk_category` column.
     *
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryStoreQuery
     */
    public function queryCategoryStoreWithStoresByFkCategory($idCategory);
}
