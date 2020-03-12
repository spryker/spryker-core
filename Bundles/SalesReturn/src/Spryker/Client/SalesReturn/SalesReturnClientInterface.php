<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturn;

use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

interface SalesReturnClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves return reasons from persistence by criteria from FilterTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves returns from Persistence by criteria from ReturnFilterTransfer.
     * - Expands found returns with return item, totals, sales order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturns(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Validates return request.
     * - Expects order items state to be returnable.
     * - Generates unique reference number.
     * - Stores return.
     * - Stores return items.
     * - Triggers return event for provided order items.
     * - Returns "isSuccessful=true" and return transfer on success or `isSuccessful=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires ReturnableItemFilterTransfer::customerReference to be set.
     * - Adds additional filter by returnable states.
     * - Retrieves order items from persistence by criteria from ReturnableItemFilterTransfer.
     * - Executes ReturnPolicyPluginInterface stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getReturnableItems(ReturnableItemFilterTransfer $returnableItemFilterTransfer): ItemCollectionTransfer;
}
