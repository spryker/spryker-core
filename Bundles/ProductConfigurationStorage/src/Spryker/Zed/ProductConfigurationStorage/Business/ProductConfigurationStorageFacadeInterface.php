<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductConfigurationStorageFacadeInterface
{
    /**
     * Specification:
     * - Extracts product configuration IDs from the $eventTransfers.
     * - Finds product configuration using product configuration IDs.
     * - Stores JSON encoded data to a storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductConfigurationEvents(
        array $eventTransfers
    ): void;

    /**
     * Specification:
     * - Finds and deletes product configuration storage entities.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByProductConfigurationEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves a product configuration storage collection from Persistence according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $productConfigurationStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getFilteredProductConfigurationStorageDataTransfers(
        FilterTransfer $filterTransfer,
        array $productConfigurationStorageIds
    ): array;
}
