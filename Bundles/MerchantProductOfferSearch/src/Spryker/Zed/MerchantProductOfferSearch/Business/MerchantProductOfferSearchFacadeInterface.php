<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business;

interface MerchantProductOfferSearchFacadeInterface
{
    /**
     * Specification:
     *  - Gets merchant ids from eventTransfers.
     *  - Retrieves a list of abstract product ids by merchant ids.
     *  - Queries all product abstract with the given abstract product ids.
     *  - Stores data as json encoded to storage table.
     *  - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     *  - Gets merchant profile ids from eventTransfers.
     *  - Retrieves a list of abstract product ids by merchant profile ids.
     *  - Queries all product abstract with the given abstract product ids.
     *  - Stores data as json encoded to storage table.
     *  - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantProfileEvents(array $eventTransfers): void;

    /**
     * Specification:
     *  - Gets merchant product offer ids from eventTransfers.
     *  - Retrieves a list of abstract product ids by product offer ids.
     *  - Queries all product abstract with the given abstract product ids.
     *  - Stores data as json encoded to storage table.
     *  - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdProductOfferEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Returns a list of merchant names grouped by product abstract ids.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return string[][]
     */
    public function getMerchantNamesByProductAbstractIds(array $productAbstractIds): array;
}
