<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
    public function findByProductOfferReference(string $productOfferReference, string $storeName): ?ProductOfferAvailabilityStorageTransfer;

    /**
     * Specification:
     * - Finds a product offer availability within Storage by given product offer references.
     *
     * @api
     *
     * @param array<string> $productOfferReferences
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer>
     */
    public function getByProductOfferReferences(array $productOfferReferences, string $storeName): array;
}
