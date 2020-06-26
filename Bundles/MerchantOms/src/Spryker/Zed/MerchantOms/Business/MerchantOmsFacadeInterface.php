<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface MerchantOmsFacadeInterface
{
    /**
     * Specification:
     * - Requires MerchantOmsTriggerRequest.merchantOrderItems transfer field to be set.
     * - Requires MerchantOmsTriggerRequest.merchant.merchantReference transfer field to be set.
     * - Finds merchant state machine process by merchant reference, uses default process name from configuration as a fallback.
     * - Dispatches an initial merchant OMS event of merchant state machine process for each merchant order item.
     * - Returns the number of transition items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return int
     */
    public function triggerForNewMerchantOrderItems(MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer): int;

    /**
     * Specification:
     * - Requires MerchantOmsTriggerRequest.merchantOrderItems transfer field to be set.
     * - Requires MerchantOmsTriggerRequest.merchantOmsEventName transfer field to be set.
     * - Dispatches a merchant OMS event for each merchant order item.
     * - Returns the number of transition items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return int
     */
    public function triggerEventForMerchantOrderItems(MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer): int;

    /**
     * Specification:
     * - Requires MerchantOmsTriggerRequest.merchantOrderItemReference transfer field to be set.
     * - Requires MerchantOmsTriggerRequest.merchantOmsEventName transfer field to be set.
     * - Dispatches a merchant OMS event for merchant order item.
     * - Returns MerchantOmsTriggerRequest.isSuccessful = true if event trigger was successful.
     * - Returns MerchantOmsTriggerRequest.isSuccessful = false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer
     */
    public function triggerEventForMerchantOrderItem(
        MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
    ): MerchantOmsTriggerResponseTransfer;

    /**
     * Specification:
     * - Finds merchant order items.
     * - Returns array of StateMachineItem transfers filled with identifier(id of merchant order item) and idItemState.
     *
     * @api
     *
     * @param int[] $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds): array;

    /**
     * Specification:
     * - Returns StateMachineProcess transfer based on criteria.
     * - Returns default StateMachineProcess transfer if process not found.
     * - Fills StateMachineProcess transfer by process state names.
     * - Calls StateMachine facade methods to get the data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    public function getMerchantOmsProcessByMerchant(
        MerchantCriteriaTransfer $merchantCriteriaTransfer
    ): StateMachineProcessTransfer;
}
