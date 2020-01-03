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
     * @deprecated Use `ProductPageSearchRepositoryInterface::getProductConcretePageSearchCollectionByFilter()` instead.
     *
     * @see \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface::getProductConcretePageSearchCollectionByFilterAndProductIds()
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfers(array $productIds): array;

    /**
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchTransfersByProductAbstractStoreMap(array $productAbstractStoreMap): array;

    /**
     * @deprecated Use `ProductPageSearchRepositoryInterface::getProductCollectionByFilter()` instead.
     *
     * @see \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface::getProductCollectionByFilter()
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function getProductEntityTransfers(array $productIds): array;

    /**
     * @module PriceProduct
     *
     * @param int[] $priceProductStoreIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByPriceProductStoreIds(array $priceProductStoreIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function getProductCollectionByFilter(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function getProductConcretePageSearchCollectionByFilterAndProductIds(FilterTransfer $filterTransfer, array $productIds): array;
}
