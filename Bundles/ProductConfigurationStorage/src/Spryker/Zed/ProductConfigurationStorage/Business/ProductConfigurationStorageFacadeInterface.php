<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Business;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;

interface ProductConfigurationStorageFacadeInterface
{
    /**
     * Specification:
     * - Extracts Product Configurations IDs from the $eventTransfers.
     * - Finds Product Configurations using product configuration IDs.
     * - Stores JSON encoded data to a storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductConfigurationStorageCollectionByProductConfigurationEvents(
        array $eventTransfers
    ): void;

    /**
     * Specification:
     * - Finds and deletes Product Configurations Storage entities.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteProductConfigurationStorageCollection(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves Product Configuration Storage from Persistence according to provided offset, limit and ids.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param array $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findProductConfigurationStorageDataTransferByIds(int $offset, int $limit, array $ids): array;

    /**
     * Specification:
     *  - Retrieves product configurations from Persistence.
     *  - Returns Product Configurations that mach given criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): ProductConfigurationCollectionTransfer;
}
