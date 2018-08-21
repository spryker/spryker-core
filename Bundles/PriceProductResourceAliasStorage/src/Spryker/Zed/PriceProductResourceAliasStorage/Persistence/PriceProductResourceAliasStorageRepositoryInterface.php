<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Persistence;

interface PriceProductResourceAliasStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage[]
     */
    public function getPriceProductAbstractStorageEntities(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return string[]
     */
    public function getProductAbstractSkuList(array $productAbstractIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage[]
     */
    public function getPriceProductConcreteStorageEntities(array $productConcreteIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return string[]
     */
    public function getProductConcreteSkuList(array $productConcreteIds): array;

    /**
     * @param int[] $priceProductStoreIds
     *
     * @return string[]
     */
    public function getProductConcreteSkuListByPriceProductStoreIds(array $priceProductStoreIds): array;
}
