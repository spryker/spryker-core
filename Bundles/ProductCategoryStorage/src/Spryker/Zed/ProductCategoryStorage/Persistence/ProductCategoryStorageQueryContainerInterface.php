<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductCategoryStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappings($idProductAbstract);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param int[] $productCategoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryByIds($productCategoryIds);

    /**
     * Specification:
     * - Returns a a query for the table `spy_product_category` filtered by primary ids.
     *
     * @api
     *
     * @param int[] $productCategoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryByProductCategoryIds($productCategoryIds): SpyProductCategoryQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
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
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdCategoryNode($idCategoryNode, $idLocale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorageQuery
     */
    public function queryProductAbstractCategoryStorageByIds(array $productAbstractIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedByIds(array $productAbstractIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param int $idNode
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function queryAllCategoryIdsByNodeId($idNode);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductAbstractIdsByCategoryIds(array $categoryIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $nodeIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryIdsByNodeIds(array $nodeIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoriesWithAttributesAndOrderByDescendant();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoryNodes();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryWithCategoryNodes(array $productAbstractIds);
}
