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
     * - Extracts product configuration IDs from the $eventTransfers.
     * - Finds product configuration using product configuration IDs.
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
     * - Finds and deletes product configuration storage entities.
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
     * - Retrieves a product configuration storage collection from Persistence according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductConfigurationStorageDataTransfersByCriteria(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): array;

    /**
     * Specification:
     *  - Retrieves product configurations from Persistence.
     *  - Returns product configuration that mach given criteria.
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
