<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business;

use Generated\Shared\Transfer\MerchantOmsEventTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantOmsFacadeInterface
{
    /**
     * Specification:
     * - Requires MerchantOrderTransfer::merchantOrderItems.
     * - Dispatch initial oms event for each merchant order item.
     * - Returns MerchantOrderResponse::isSuccessful = true if at least one transition has been completed.
     * - Returns MerchantOrderResponse::isSuccessful = false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderResponseTransfer
     */
    public function dispatchNewMerchantOrderEvent(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderResponseTransfer;

    /**
     * Specification:
     * - Requires MerchantOrderItemTransfer::idMerchantOrderItem.
     * - Requires MerchantOmsEventTransfer::eventName.
     * - Dispatch specific oms event for merchant order item.
     * - Returns MerchantOrderItemResponse::isSuccessful = true if at least one transition has been completed.
     * - Returns MerchantOrderItemResponse::isSuccessful = false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsEventTransfer $merchantOmsEventTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function dispatchMerchantOrderItemEvent(
        MerchantOrderItemTransfer $merchantOrderItemTransfer,
        MerchantOmsEventTransfer $merchantOmsEventTransfer
    ): MerchantOrderItemResponseTransfer;

    /**
     * Specification:
     * - Requires MerchantOrderItemCollectionTransfer::merchantOrderItems.
     * - Requires MerchantOmsEventTransfer::eventName.
     * - Dispatch specific oms event for merchant order item collection.
     * - Returns MerchantOrderItemResponse::isSuccessful = true if at least one transition has been completed.
     * - Returns MerchantOrderItemResponse::isSuccessful = false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsEventTransfer $merchantOmsEventTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function dispatchMerchantOrderItemsEvent(
        MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer,
        MerchantOmsEventTransfer $merchantOmsEventTransfer
    ): MerchantOrderItemResponseTransfer;
}
