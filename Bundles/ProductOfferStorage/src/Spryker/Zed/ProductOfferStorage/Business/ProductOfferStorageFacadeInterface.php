<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Business;

interface ProductOfferStorageFacadeInterface
{
    /**
     * Specification:
     * - Gets concreteSkus from eventTransfers.
     * - Queries all active product offer with the given concreteSkus.
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
    public function writeProductConcreteProductOffersStorageCollectionByProductEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets concreteSkus from eventTransfers.
     * - Finds and deletes product concrete offer storage entities by the given concreteSkus.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorageCollectionByProductEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets productOfferReferences from eventTransfers.
     * - If there is no productOfferReferences, then takes product offer ids from eventTransfers.
     * - Queries all active product offer with the given productOfferReferences or product offer ids.
     * - Stores data as json encoded to storage table.
     * - Removes all inactive product offer storage entities with the given productOfferReferences or product offer IDs.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets productOfferReferences from eventTransfers.
     * - Finds and deletes product offer storage entities with the given productOfferReferences.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteProductOfferStorageCollectionByProductOfferEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets product offer IDs from eventTransfers.
     * - Queries all active product offers with the given product offer IDs.
     * - Stores data as json encoded to storage table.
     * - Removes all inactive product offer storage entities with the given product offer IDs.
     * - Sends a copy of data to queue based on module config.
     * - Executes `ProductOfferStorageFilterPluginInterface` plugin stack.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductOfferStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets product offer IDs and store IDs from eventTransfers.
     * - Finds and deletes product offer storage entities by the given product offer IDs and store IDs.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByProductOfferStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets product offer IDs and obtains the corresponding product SKUs from eventTransfers.
     * - Queries all active product offers with the given SKUs.
     * - Stores data as json encoded to storage table.
     * - Removes all inactive product offer storage entities with the given SKUs.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByProductOfferStoreEvents(array $eventTransfers): void;
}
