<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Persistence;

interface ProductListStorageRepositoryInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer[]
     */
    public function findProductAbstractProductListStorageEntities(array $productAbstractIds): array;

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer[]
     */
    public function findProductConcreteProductListStorageEntities(array $productConcreteIds): array;

    /**
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function findProductConcreteIdsByProductListIds(array $productListIds): array;

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    public function findProductAbstractIdsByCategoryIds(array $categoryIds): array;
}
