<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductPageSearchRepositoryInterface
{
    /**
     * @param array<int> $productSearchIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByProductSearchIds(array $productSearchIds): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function getProductConcretePageSearchTransfers(array $productIds): array;

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductConcreteSkus(array $productConcreteSkus): array;

    /**
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function getProductConcretePageSearchTransfersByProductAbstractStoreMap(array $productAbstractStoreMap): array;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductEntityTransfer>
     */
    public function getProductEntityTransfers(array $productIds): array;

    /**
     * @module PriceProduct
     *
     * @param array<int> $priceProductStoreIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByPriceProductStoreIds(array $priceProductStoreIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductIds(FilterTransfer $filterTransfer, array $productIds = []): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int>
     */
    public function getEligibleForAddToCartProductAbstractsIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<string>
     */
    public function getProductConcreteSkusByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getConcreteProductsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param list<int> $productImageSetIds
     *
     * @return list<int>
     */
    public function getProductAbstractIdsByProductImageSetIds(array $productImageSetIds): array;

    /**
     * @param list<int> $categoryNodeIds
     *
     * @return list<int>
     */
    public function getCategoryIdsByCategoryNodeIds(array $categoryNodeIds): array;

    /**
     * @param list<int> $categoryIds
     *
     * @return list<int>
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;
}
