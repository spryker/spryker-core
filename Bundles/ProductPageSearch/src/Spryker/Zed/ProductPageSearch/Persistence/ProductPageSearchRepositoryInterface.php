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
     * @param array<string, int> $productConcreteSkuTimestampMap
     *
     * @return array<int, int>
     */
    public function getProductAbstractIdTimestampMap(array $productConcreteSkuTimestampMap): array;

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
     * @module PriceProduct
     *
     * @param array<int, int> $priceProductStoreIdTimestampMap
     *
     * @return array<int>
     */
    public function getProductAbstractIdTimestampMapByPriceProductStoreIds(array $priceProductStoreIdTimestampMap): array;

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
     * @module ProductImage
     *
     * @param array<int, int> $productImageSetIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getProductAbstractIdsByProductImageSetIds(array $productImageSetIdTimestampMap): array;

    /**
     * @module ProductImage
     *
     * @param array<int, int> $productImageSetIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getConcreteProductIdTimestampMapByProductImageSetIds(array $productImageSetIdTimestampMap): array;

    /**
     * @module ProductImage
     *
     * @param array<int, int> $productImageIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getConcreteProductIdTimestampMapByProductImageIds(array $productImageIdTimestampMap): array;

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

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getProductAbstractIdTimestampMapByProductIds(array $productIdTimestampMap): array;

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getConcreteProductIdTimestampMapByProductAbstractIds(array $productAbstractIdTimestampMap): array;

    /**
     * @param array<int, int> $productImageIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getProductAbstractIdTimestampMapByProductImageId(array $productImageIdTimestampMap): array;

    /**
     * @param array<int, int> $priceTypeIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getAllProductAbstractIdTimestampMapByPriceTypeIds(array $priceTypeIdTimestampMap): array;

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getAllProductAbstractIdTimestampMapByPriceProductIds(array $productIdTimestampMap): array;

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getRelevantProductAbstractIdsToUpdate(array $productAbstractIdTimestampMap): array;

    /**
     * @param array<int, int> $productIdTimestampMap
     *
     * @return array<int, int>
     */
    public function getRelevantProductConcreteIdsToUpdate(array $productIdTimestampMap): array;
}
