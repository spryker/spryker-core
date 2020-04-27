<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductLabelStorageFacadeInterface
{
    /**
     * Specification:
     * - Stores label dictionary data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     * - Deletes label dictionary storage entities if dictionary is empty.
     *
     * @api
     *
     * @return void
     */
    public function writeProductLabelDictionaryStorageCollection(): void;

    /**
     * Specification:
     * - Finds and deletes label dictionary storage entities.
     * - Sends delete message to queue based on module config.
     * - Deletes label dictionary storage entities if dictionary is empty.
     *
     * @api
     *
     * @return void
     */
    public function deleteProductLabelDictionaryStorageCollection(): void;

    /**
     * Specification:
     * - Extracts product abstract ids from event transfers.
     * - Queries all product labels with the given product abstract ids.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Queries all product labels the given $eventTransfer by ProductLabelProductAbstractEvents.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves a collection of product abstract label storage transfers according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductAbstractLabelStorageDataTransfersByIds(FilterTransfer $filterTransfer, array $ids): array;

    /**
     * Specification:
     * - Retrieves a collection of product label dictionary storage transfers according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductLabelDictionaryStorageDataTransfersByIds(FilterTransfer $filterTransfer, array $ids): array;
}
