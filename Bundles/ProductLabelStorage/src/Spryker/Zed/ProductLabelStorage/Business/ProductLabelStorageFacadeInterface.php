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
     * - Stores label dictionary data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     * - Deletes label dictionary storage entities if dictionary is empty
     *
     * @api
     *
     * @deprecated Use {@link writeProductLabelDictionaryStorageCollection()} instead.
     *
     * @return void
     */
    public function publishLabelDictionary();

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
     * - Finds and deletes label dictionary storage entities
     * - Sends delete message to queue based on module config
     * - Deletes label dictionary storage entities if dictionary is empty
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::deleteProductLabelDictionaryStorageCollection()} instead.
     *
     * @return void
     */
    public function unpublishLabelDictionary();

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
     * - Queries all productLabels with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents()}
     *   or {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents()} instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishProductLabel(array $productAbstractIds);

    /**
     * Specification:
     * - Extracts product abstract IDs from $eventTransfers created by product label events.
     * - Finds product labels using product abstract IDs.
     * - Stores JSON encoded data to a storage table.
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
     * - Extracts product abstract IDs from the $eventTransfers created by product label product abstract events.
     * - Finds product labels using product abstract IDs.
     * - Stores JSON encoded data to a storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents(
        array $eventTransfers
    ): void;

    /**
     * Specification:
     * - Finds and deletes productLabels storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @deprecated Use {@link writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents()}
     *  or {@link writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents()} instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds);

    /**
     * Specification:
     * - Retrieves a collection of product abstract label storage transfers according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productAbstractLabelStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductAbstractLabelStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productAbstractLabelStorageIds
    ): array;

    /**
     * Specification:
     * - Retrieves a collection of product label dictionary storage transfers according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productLabelDictionaryStorageIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getProductLabelDictionaryStorageDataTransfersByIds(
        FilterTransfer $filterTransfer,
        array $productLabelDictionaryStorageIds
    ): array;
}
