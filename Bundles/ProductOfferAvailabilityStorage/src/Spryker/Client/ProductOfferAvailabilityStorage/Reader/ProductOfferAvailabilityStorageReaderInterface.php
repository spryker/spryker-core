<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Reader;

use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;

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
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer[]
     */
    public function getByProductOfferReferences(array $productOfferReferences, string $storeName): array;
}
