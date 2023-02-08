<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business;

use Generated\Shared\Transfer\PaginationTransfer;

interface CustomerStorageFacadeInterface
{
    /**
     * Specification:
     * - Used in case if customer was invalidated or customer's password was changed.
     * - Publishes customer data to storage based on customer publish event.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCustomerInvalidatedStorageCollectionByCustomerEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Deletes customer invalidated storage data if entity creation date is older than entity life time period.
     * - Uses {@see \Spryker\Zed\CustomerStorage\CustomerStorageConfig::getCustomerInvalidatedStorageRecordLifeTime()}
     *
     * @api
     *
     * @return void
     */
    public function deleteExpiredCustomerInvalidatedStorage(): void;

    /**
     * Specification:
     * - Returns list of SynchronizationData transfers according to provided offset, limit and ids.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param array<int, int> $customerIds
     *
     * @return array<int, \Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransferCollection(
        PaginationTransfer $paginationTransfer,
        array $customerIds
    ): array;
}
