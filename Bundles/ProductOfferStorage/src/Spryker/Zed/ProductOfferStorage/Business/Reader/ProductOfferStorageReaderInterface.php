<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;

interface ProductOfferStorageReaderInterface
{
    /**
     * @param array $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferSellableCollectionByProductOfferIds(array $productOfferIds): ProductOfferCollectionTransfer;

    /**
     * @param array<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductOfferIds(array $productOfferIds): ProductOfferCollectionTransfer;

    /**
     * @param array<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductOfferReferences(array $productOfferReferences): ProductOfferCollectionTransfer;

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductConcreteSkus(array $productConcreteSkus): ProductOfferCollectionTransfer;

    /**
     * @param array<int> $productOfferIds
     * @param array<int> $storeIds
     *
     * @return array<string, array<string>>
     */
    public function getProductOfferReferencesGroupedByStore(array $productOfferIds, array $storeIds): array;
}
