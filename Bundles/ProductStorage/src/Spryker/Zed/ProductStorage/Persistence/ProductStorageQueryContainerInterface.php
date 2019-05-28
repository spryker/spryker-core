<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractByIds(array $productAbstractIds);

    /**
     * Specification:
     * - Returns a a query for the table `spy_product_abstract` filtered by product abstract ids.
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractByProductAbstractIds(array $productAbstractIds): SpyProductAbstractQuery;

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryProductConcreteByIds(array $productIds);

    /**
     * Specification:
     * - Returns a a query for the table `spy_product` filtered by product ids.
     *
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteByProductIds(array $productIds): SpyProductQuery;

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery
     */
    public function queryProductAbstractStorageByIds(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorageQuery
     */
    public function queryProductConcreteStorageByIds(array $productIds);

    /**
     * @api
     *
     * @param bool $isSuper
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey($isSuper = true);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryConcreteProduct($idProductAbstract, $idLocale);

    /**
     * @api
     *
     * @param int[] $productAbstractIds
     * @param int[] $localeIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryConcreteProductBulk(array $productAbstractIds, array $localeIds);

    /**
     * @api
     *
     * @param array $attributeKeys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKeyByKey(array $attributeKeys);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductIdsByProductAbstractIds(array $productAbstractIds);
}
