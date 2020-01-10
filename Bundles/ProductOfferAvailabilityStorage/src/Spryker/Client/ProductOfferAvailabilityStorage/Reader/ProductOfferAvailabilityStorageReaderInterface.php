<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Reader;

use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductOfferAvailabilityStorageReaderInterface
{
    /**
     * @param string $productOfferReference
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer|null
     */
    public function findByProductOfferReference(string $productOfferReference, string $storeName): ?ProductOfferAvailabilityStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductOfferAvailableForStore(ProductOfferTransfer $productOfferTransfer, StoreTransfer $storeTransfer): bool;
}
