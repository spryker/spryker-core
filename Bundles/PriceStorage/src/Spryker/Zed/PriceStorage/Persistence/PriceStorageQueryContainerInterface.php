<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface PriceStorageQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductAbstractByIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorageQuery
     */
    public function queryPriceAbstractStorageByPriceAbstractIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryAllProductAbstractIdsByPriceTypeIds(array $priceTypeIds);

    /**
     * @api
     *
     * @param array $priceTypeIds
     *
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorageQuery
     */
    public function queryAllProductIdsByPriceTypeIds(array $priceTypeIds);

    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceAbstractStorageQuery
     */
    public function queryPriceProductConcreteByIds(array $productConcreteIds);

    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return \Orm\Zed\PriceStorage\Persistence\SpyPriceConcreteStorageQuery
     */
    public function queryPriceConcreteStorageByProductIds(array $productConcreteIds);

}
