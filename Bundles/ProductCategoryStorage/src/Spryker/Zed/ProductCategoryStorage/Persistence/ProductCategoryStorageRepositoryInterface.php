<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductCategoryStorageRepositoryInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\CategoryNodeAggregationTransfer>
     */
    public function getAllCategoryNodeAggregationsOrderedByDescendant(): array;

    /**
     * @return array<int>
     */
    public function getAllCategoryNodeIds(): array;

    /**
     * @param int $idCategoryNode
     *
     * @return array<int>
     */
    public function getAllCategoryIdsByCategoryNodeId(int $idCategoryNode): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer>
     */
    public function getProductAbstractLocalizedAttributes(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductCategoryTransfer>
     */
    public function getProductCategoryWithCategoryNodes(array $productAbstractIds, string $storeName): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<array<array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>>>
     */
    public function getMappedProductAbstractCategoryStorages(array $productAbstractIds): array;

    /**
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;

    /**
     * @param int $offset
     * @param int $limit
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductAbstractCategoryStorageSynchronizationDataTransfersByProductAbstractIds(
        int $offset,
        int $limit,
        array $productAbstractIds
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductCategoryTransfer>
     */
    public function getProductCategoryTransfersByFilter(FilterTransfer $filterTransfer): array;
}
