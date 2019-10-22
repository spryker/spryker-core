<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductConcreteProductOffersStorageCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaFilterTransfer;

interface MerchantProductOfferStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductOffersStorageCriteriaFilterTransfer $productConcreteProductOffersStorageCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer[]
     */
    public function findProductConcreteProductOffersStorage(ProductConcreteProductOffersStorageCriteriaFilterTransfer $productConcreteProductOffersStorageCriteriaFilterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaFilterTransfer $productOfferStorageCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function findProductOfferStorage(ProductOfferStorageCriteriaFilterTransfer $productOfferStorageCriteriaFilterTransfer): array;
}
