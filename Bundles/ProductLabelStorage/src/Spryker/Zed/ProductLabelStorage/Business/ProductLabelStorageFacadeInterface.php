<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

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
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::writeProductLabelDictionaryStorageCollection()} instead.
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
     *              or {@link \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents()} instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishProductLabel(array $productAbstractIds);

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
     * - Finds and deletes productLabels storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds);
}
