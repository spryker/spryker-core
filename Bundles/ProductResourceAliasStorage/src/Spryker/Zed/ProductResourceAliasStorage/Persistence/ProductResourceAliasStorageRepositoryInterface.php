<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Persistence;

interface ProductResourceAliasStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[]
     */
    public function getProductAbstractStorageEntities(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return string[][]
     */
    public function getProductAbstractSkuList(array $productAbstractIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage[]
     */
    public function getProductConcreteStorageEntities(array $productConcreteIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return string[][]
     */
    public function getProductConcreteSkuList(array $productConcreteIds): array;
}
