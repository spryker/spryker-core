<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Persistence;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface PriceProductStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractSkuByIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery
     */
    public function queryPriceAbstractStorageByPriceAbstractIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryAllProductAbstractIdsByPriceTypeIds(array $priceTypeIds);

    /**
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorageQuery
     */
    public function queryAllProductIdsByPriceTypeIds(array $priceTypeIds);

    /**
     * @api
     *
     * @param array $priceProductIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryAllProductIdsByPriceProductIds(array $priceProductIds);

    /**
     * @api
     *
     * @param array $priceProductIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryAllProductAbstractIdsByPriceProductIds(array $priceProductIds);

    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductAbstractIdsByProductConcreteIds(array $productConcreteIds);

    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorageQuery
     */
    public function queryPriceConcreteStorageByProductIds(array $productConcreteIds);

    /**
     * @api
     *
     * @param int[] $priceProductStoreIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryProductAbstractIdsByPriceProductStoreIds(array $priceProductStoreIds): SpyPriceProductQuery;

    /**
     * @api
     *
     * @param int[] $priceProductStoreIds
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryProductConcreteIdsByPriceProductStoreIds(array $priceProductStoreIds): SpyPriceProductQuery;
}
