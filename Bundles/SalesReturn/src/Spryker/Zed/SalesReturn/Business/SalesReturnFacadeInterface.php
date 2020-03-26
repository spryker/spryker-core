<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

interface SalesReturnFacadeInterface
{
    /**
     * Specification:
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
     * - Applies default offset, limit, sort field and direction.
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
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer;

    /**
     * Specification:
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

    /**
     * Specification:
     * - Requires ItemTransfer::idSalesOrderItem to be set.
     * - Retrieves item by idSalesOrderItem.
     * - Copies ItemTransfer::refundableAmount to ItemTransfer::remunerationAmount.
     * - Persists ItemTransfer afterward.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function setItemRemunerationAmount(ItemTransfer $itemTransfer): void;

    /**
     * Specification:
     * - Requires OrderTransfer::totals to be set.
     * - Sums each item remuneration amount to total.
     * - Expands Totals with remuneration amount total.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderTotalsWithRemunerationTotal(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Verifies difference between order item creation date and config const.
     * - If difference more than config const, sets `Item::isReturnable=false`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithIsReturnable(array $itemTransfers): array;

    /**
     * Specification:
     * - Requires ItemTransfer::state.
     * - Requires ItemStateTransfer::name.
     * - Compares order item state with returnable states.
     * - If item state is not applicable for return, sets `Item::isReturnable=false`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithIsReturnableByItemState(array $itemTransfers): array;
}
