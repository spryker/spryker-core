<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business;

interface MerchantProductStorageFacadeInterface
{
    /**
     * Specification:
     * - Gets idProductAbstracts from eventTransfers.
     * - Queries all merchant product abstracts with the given idProductAbstracts.
     * - Returns a list of merchant reference for idProductAbstract.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeMerchantProductStorageCollectionByIdProductAbstractEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets idProductAbstracts from eventTransfers.
     * - Finds and write merchant product abstract storage entities with the given idProductAbstracts.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteMerchantProductStorageCollectionByIdProductAbstractEvents(array $eventTransfers): void;
}
