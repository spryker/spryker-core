<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business;

use Generated\Shared\Transfer\ProductPageLoadTransfer;

interface ProductLabelSearchFacadeInterface
{
    /**
     * Specification:
     * - Gets product label IDs from $eventTransfers.
     * - Retrieves a list of abstract product ids by product label IDs.
     * - Queries all product abstract with the given abstract product IDs.
     * - Stores data as JSON encoded to search table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets abstract product IDs from eventTransfers.
     * - Queries all product abstract with the given abstract product IDs.
     * - Stores data as JSON encoded to search table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelProductAbstractEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets abstract product IDs from $eventTransfers.
     * - Retrieves a list of abstract products with the given abstract product IDs.
     * - Stores data as JSON encoded to search table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Expands product page load transfer with product label ids mapped by id product abstract and store name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransferWithProductLabelIds(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer;
}
