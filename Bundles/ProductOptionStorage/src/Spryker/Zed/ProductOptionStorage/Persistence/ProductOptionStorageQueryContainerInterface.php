<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Persistence;

use Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductOptionStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorageQuery
     */
    public function queryProductAbstractOptionStorageByIds(array $productAbstractIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
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
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryProductOptionsByProductAbstractIds(array $productAbstractIds);

    /**
     * Specification:
     * - Returns a a query for the table `spy_product_abstract_product_option_group` filtered by product abstract ids.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryProductAbstractOptionsByProductAbstractIds(array $productAbstractIds): SpyProductAbstractProductOptionGroupQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productOptionGroupsIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryProductAbstractIdsByProductGroupOptionByIds(array $productOptionGroupsIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productOptionValuesIds
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryProductAbstractIdsByProductValueOptionByIds(array $productOptionValuesIds);
}
