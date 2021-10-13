<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Persistence;

interface ProductResourceAliasStorageRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage>
     */
    public function getProductAbstractStorageEntities(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<string[]>
     */
    public function getProductAbstractSkuList(array $productAbstractIds): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<\Orm\Zed\ProductStorage\Persistence\SpyProductConcreteStorage>
     */
    public function getProductConcreteStorageEntities(array $productConcreteIds): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<string[]>
     */
    public function getProductConcreteSkuList(array $productConcreteIds): array;
}
