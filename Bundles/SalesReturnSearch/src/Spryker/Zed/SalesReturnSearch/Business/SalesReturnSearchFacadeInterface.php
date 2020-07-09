<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface SalesReturnSearchFacadeInterface
{
    /**
     * Specification:
     * - Retrieves all Return Reasons using IDs from $eventTransfers.
     * - Updates entities from `spy_sales_return_reason_search` with actual data from obtained Return Reasons.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByReturnReasonEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Deletes entities from `spy_sales_return_reason_search` based on IDs from $eventTransfers.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByReturnReasonEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Reads entities from `spy_sales_return_reason_search` based on criteria from FilterTransfer and $returnReasonIds.
     * - Returns array of SynchronizationDataTransfer filled with data from search entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $returnReasonIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getReturnReasonSynchronizationDataTransfersByIds(FilterTransfer $filterTransfer, array $returnReasonIds = []): array;
}
