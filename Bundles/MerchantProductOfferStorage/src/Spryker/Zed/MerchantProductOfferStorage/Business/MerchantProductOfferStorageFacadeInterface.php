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
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByMerchantEvents(array $eventTransfers): void;

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
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByProductSkuEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets concreteSkus from eventTransfers.
     * - Finds and deletes product concrete offer storage entities with the given concreteSkus.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorageCollectionByProductSkuEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets productOfferReferences from eventTransfers.
     * - Queries all active product offer with the given productOfferReferences.
     * - Stores data as json encoded to storage table.
     * - Removes all inactive product offer storage entities with the given productOfferReferences.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferStorageCollectionByProductOfferReferenceEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets productOfferReferences from eventTransfers.
     * - Finds and deletes product offer storage entities with the given productOfferReferences.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteProductOfferStorageCollectionByProductOfferReferenceEvents(array $eventTransfers): void;
}
