<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business;

interface MerchantProductOfferStorageFacadeInterface
{
    /**
     * Specification:
     * - Gets merchantIds from eventTransfers.
     * - Queries all active product offer with the given merchantIds.
     * - Returns a list of product references for concrete sku.
     * - Stores data as json encoded to storage table.
     * - Removes all inactive product offer storage entities with the given concreteSkus.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets merchantIds from eventTransfers.
     * - Queries all active product offer with the given merchantIds.
     * - Gets a list of product references for concrete sku.
     * - Stores data as json encoded to storage table.
     * - Removes all inactive product offer storage entities with the given concreteSkus.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Filters product offer services collection by active and approved merchants.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    public function filterProductOfferServices(array $productOfferServicesTransfers): array;
}
