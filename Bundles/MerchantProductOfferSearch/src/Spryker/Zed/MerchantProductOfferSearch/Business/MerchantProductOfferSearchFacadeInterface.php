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
     *  - Retrieve list of abstract product ids by merchant ids.
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
    public function writeMerchantProductOfferSearchCollectionByIdMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     *  - Gets merchant profile ids from eventTransfers.
     *  - Retrieve list of abstract product ids by merchant profile ids.
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
    public function writeMerchantProductOfferSearchCollectionByIdMerchantProfileEvents(array $eventTransfers): void;

    /**
     * Specification:
     *  - Gets merchant product offer ids from eventTransfers.
     *  - Retrieve list of abstract product ids by product offer ids.
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
    public function writeMerchantProductOfferSearchCollectionByIdMerchantProductOfferEvents(array $eventTransfers): void;
}
