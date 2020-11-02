<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface MerchantSearchFacadeInterface
{
    /**
     * Specification:
     * - Retrieves all Merchants using IDs from $eventTransfers.
     * - Updates entities from `spy_merchant_search` with actual data from obtained Merchants.
     * - Runs merchant search data expander plugins
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves all Merchants using foreign keys from $eventTransfers.
     * - Updates entities from `spy_merchant_search` with actual data from obtained Merchants.
     * - Runs merchant search data expander plugins
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantCategoryEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Deletes entities from `spy_merchant_search` based on:
     *     - IDs from `$eventTransfers`
     *     - Merchant activity
     *     - Merchant status
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByMerchantEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Reads entities from `spy_merchant_search` based on criteria from FilterTransfer and `$merchantIds`.
     * - Returns array of SynchronizationDataTransfer filled with data from search entities.

     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersByMerchantIds(FilterTransfer $filterTransfer, array $merchantIds): array;
}
