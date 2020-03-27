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
     * @deprecated Use `ProductLabelStorageFacadeInterface::writeCollectionByProductLabelEvents()` instead.
     * @see `\Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::writeCollectionByProductLabelEvents()`
     *
     * @api
     *
     * @return void
     */
    public function publishLabelDictionary();

    /**
     * Specification:
     * - Extracts product label ids from event transers
     * - Finds active product labels by the product label ids
     * - Prepares a new json collection based on localized attributes of the products labels
     * - Finds all storage data
     * - Compare new prepared collection with founded storage data and form new collection to update
     * - Stores collection to update as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     * - Deletes label dictionary storage entities if dictionary is empty
     *
     * @deprecated Use `ProductLabelStorageFacadeInterface::writeCollectionByProductLabelEvents()` instead.
     * @see `\Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface::writeCollectionByProductLabelEvents()`
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Finds and deletes label dictionary storage entities
     * - Sends delete message to queue based on module config
     * - Deletes label dictionary storage entities if dictionary is empty
     *
     * @api
     *
     * @return void
     */
    public function unpublishLabelDictionary();

    /**
     * Specification:
     * - Finds storage dictionary items by product label id and deletes
     * - Sends delete message to queue based on module config
     * - Deletes label dictionary storage entities if dictionary is empty
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByProductLabelEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Queries all productLabels with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishProductLabel(array $productAbstractIds);

    /**
     * Specification:
     * - Finds and deletes productLabels storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds);
}
