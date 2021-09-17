<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductListStorageRepositoryInterface
{
    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function findProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage>
     */
    public function findProductAbstractProductListStorageEntities(array $productAbstractIds): array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<\Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage>
     */
    public function findProductConcreteProductListStorageEntities(array $productConcreteIds): array;

    /**
     * @return array<\Orm\Zed\ProductListStorage\Persistence\SpyProductAbstractProductListStorage>
     */
    public function findAllProductAbstractProductListStorageEntities(): array;

    /**
     * @return array<\Orm\Zed\ProductListStorage\Persistence\SpyProductConcreteProductListStorage>
     */
    public function findAllProductConcreteProductListStorageEntities(): array;

    /**
     * @param array<int> $productListIds
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByProductListIds(array $productListIds): array;

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductConcreteProductListStorageEntityTransfer>
     */
    public function findFilteredProductConcreteProductListStorageEntities(FilterTransfer $filterTransfer, array $productConcreteIds = []): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductAbstractProductListStorageEntityTransfer>
     */
    public function findFilteredProductAbstractProductListStorageEntities(FilterTransfer $filterTransfer, array $productAbstractIds = []): array;

    /**
     * @return int
     */
    public function getProductListWhitelistEnumValue(): int;

    /**
     * @return int
     */
    public function getProductListBlacklistEnumValue(): int;
}
