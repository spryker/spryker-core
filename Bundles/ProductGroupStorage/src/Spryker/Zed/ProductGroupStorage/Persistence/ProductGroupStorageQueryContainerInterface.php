<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductGroupStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productGroupIds
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryProductAbstractGroupByGroupIds(array $productGroupIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryProductAbstractGroupByProductAbstractIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductGroupStorage\Persistence\SpyProductAbstractGroupStorageQuery
     */
    public function queryProductAbstractGroupStorageByIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedWithGroupByIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedByIds(array $productAbstractIds);
}
