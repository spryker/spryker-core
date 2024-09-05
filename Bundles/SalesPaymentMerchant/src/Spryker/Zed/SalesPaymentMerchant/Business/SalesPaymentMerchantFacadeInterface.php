<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface SalesPaymentMerchantFacadeInterface
{
    /**
     * Specification:
     * - Checks if the used payment method is configured to support payout.
     * - Requires the `ItemTransfer.merchantReference` property to be set.
     * - Expects the `OrderTransfer.orderReference` property to be set.
     * - If the `ItemTransfer.merchantReference` is not set, returns true.
     * - If the transfer endpoint URL is not found, returns true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $salesOrderItemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPayoutSupportedForPaymentMethodUsedForOrder(
        ItemTransfer $salesOrderItemTransfer,
        OrderTransfer $orderTransfer
    ): bool;

    /**
     * Specification:
     * - Sends a synchronous request to the PSP App to perform money transfers to merchants.
     * - The following properties are required in case of merchant item:
     * - Requires the `ItemTransfer.merchantReference` property to be set.
     * - Requires the `ItemTransfer.orderItemReference` property to be set.
     * - Requires the `ItemTransfer.sumPriceToPayAggregation` property to be set.
     * - Requires the `OrderTransfer.orderReference` property to be set.
     * - Requires the `ExpenseTransfer.merchantReference` property to be set.
     * - Requires the `ExpenseTransfer.orderReference` property to be set.
     * - Requires the `ExpenseTransfer.uuid` property to be set.
     * - Requires the `ExpenseTransfer.sumPriceToPayAggregation` property to be set.
     * - If the transfer endpoint URL is not found, returns without performing the transfer.
     * - Fetches the order items and expenses for the transfer.
     * - Calculates the payout amount using {@link \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface::calculatePayoutAmount} if it's set.
     * - Prepares the order expenses to be transferred if {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::isOrderExpenseIncludedInPaymentProcess()} is set.
     * - Otherwise, the order expenses are not transferred.
     * - Uses the following configuration to filter out the order expenses for store by type {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::getExcludedExpenseTypesForStore()}.
     * - Checks if the order expenses has been already transferred for the merchant order, in case of yes, skips the transfer.
     * - Sends the transfer request to the PSP App.
     * - Saves the transfer response to the persistence.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function payoutMerchants(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): void;

    /**
     * Specification:
     * - Checks if the used payment method is configured to support reverse payout.
     * - Requires the `ItemTransfer.merchantReference` property to be set.
     * - Expects the `OrderTransfer.orderReference` property to be set.
     * - If the `ItemTransfer.merchantReference` is not set, returns true.
     * - If the transfer endpoint URL is not found, returns true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $salesOrderItemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPayoutReversalSupportedForPaymentMethodUsedForOrder(
        ItemTransfer $salesOrderItemTransfer,
        OrderTransfer $orderTransfer
    ): bool;

    /**
     * Specification:
     * - Send a synchronous Request to the PSP App to do refund of previously made money transfers to merchants.
     * - The following properties are required in case of merchant item:
     * - Requires the `ItemTransfer.merchantReference` property to be set.
     * - Requires the `ItemTransfer.orderItemReference` property to be set.
     * - Requires the `ItemTransfer.sumPriceToPayAggregation` property to be set.
     * - Requires the `ItemTransfer.canceledAmount` property to be set.
     * - Requires the `OrderTransfer.orderReference` property to be set.
     * - Requires the `ExpenseTransfer.merchantReference` property to be set.
     * - Requires the `ExpenseTransfer.orderReference` property to be set.
     * - Requires the `ExpenseTransfer.uuid` property to be set.
     * - Requires the `ExpenseTransfer.refundableAmount` property to be set.
     * - Requires the `ExpenseTransfer.canceledAmount` property to be set in case `ExpenseTransfer.refundableAmount` is not set.
     * - If the transfer endpoint URL is not found, returns without performing the transfer.
     * - Fetches the order items and expenses for the transfer.
     * - Calculates the payout reverse amount using {@link \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface::calculatePayoutAmount} if it's set.
     * - Prepares the order expenses to be transferred if {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::isOrderExpenseIncludedInPaymentProcess()} is set.
     * - Otherwise, the order expenses are not transferred.
     * - Uses the following configuration to filter out the order expenses for store by type {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::getExcludedExpenseTypesForStore()}.
     * - The order expenses are not transferred until at least one order item is in the refused state {@link \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig::getItemRefusedStates()}.
     * - Sends the transfer reverse request to the PSP App.
     * - Saves the transfer response to the persistence.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function reversePayoutMerchants(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): void;
}
