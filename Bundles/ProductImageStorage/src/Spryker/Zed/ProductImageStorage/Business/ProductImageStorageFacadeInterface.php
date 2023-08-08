<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Business;

interface ProductImageStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all productImages with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publishProductAbstractImages(array $productAbstractIds);

    /**
     * Specification:
     * - Finds and deletes productImages storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstractImages(array $productAbstractIds);

    /**
     * Specification:
     * - Queries all productImages with the given productIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function publishProductConcreteImages(array $productIds);

    /**
     * Specification:
     * - Finds and deletes productImages storage entities with the given productIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function unpublishProductConcreteImages(array $productIds);

    /**
     * Specification:
     * - Removes and updates product abstract image data by `SpyProductImageSetToProductImage` entity events.
     * - Extracts product image set IDs from the `$eventEntityTransfers` created by product image set to product image entity events.
     * - Finds product abstract IDs by product image set IDs.
     * - Collects product abstract image data.
     * - Updates product abstract image storage entities with modified image data.
     * - Finds and deletes product abstract image storage entities without image data.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteProductAbstractImageStorageCollectionByProductImageSetToProductImageEvents(array $eventEntityTransfers): void;
}
