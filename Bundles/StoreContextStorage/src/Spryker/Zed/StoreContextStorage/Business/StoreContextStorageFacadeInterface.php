<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextStorage\Business;

interface StoreContextStorageFacadeInterface
{
    /**
     * Specification:
     * - Extracts store IDs from the `eventEntityTransfers` foreign keys.
     * - Stores JSON encoded data to storage table.
     * - Sends a copy of data to the queue based on module config.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeStoreContextStorageCollectionByStoreEvents(array $eventEntityTransfers): void;
}
