<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;

interface MerchantProductOfferStorageRepositoryInterface
{
    /**
     * @param int[] $merchantIds
     *
     * @return string[]
     */
    public function getProductConcreteSkusByMerchantIds(array $merchantIds): array;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOffersByFilterCriteria(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer): ProductOfferCollectionTransfer;
}
