<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage;

use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;

interface ProductOfferAvailabilityStorageClientInterface
{
    /**
     * Specification:
     * - Finds a product offer availability within Storage by given product offer reference.
     * - Returns null if product offer availability was not found.
     *
     * @api
     *
     * @param string $productOfferReference
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer|null
     */
    public function findAvailabilityByProductOfferReference(string $productOfferReference, string $storeName): ?ProductOfferAvailabilityStorageTransfer;
}
