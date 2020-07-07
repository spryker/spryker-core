<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business;

interface MerchantProductStorageFacadeInterface
{
    /**
     * Specification:
     * - Gets idMerchantProducts from eventTransfers.
     * - Finds merchant products by ids.
     * - Finds product abstract ids for merchant products.
     * - Runs product storage publisher for found product abstract ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantProductAbstractEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets product abstract ids from eventTransfers.
     * - Runs product storage publisher with found product abstract ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByIdProductAbstractEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Gets merchant ids from eventTransfers.
     * - Finds product abstract ids by merchant ids.
     * - Runs product storage publisher with found product abstract ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByIdMerchantEvents(array $eventTransfers): void;
}
