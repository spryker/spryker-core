<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business;

use Generated\Shared\Transfer\EventPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesPaymentFacadeInterface
{
    /**
     * Specification:
     * - Saves order payments to the database spy_sales_payment.
     * - Executes {@link \Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayments(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     *  - Expands order transfer with payments data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithPayments(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     * - Validates if capturing process can be executed, throws `EventExecutionForbiddenException` on failure.
     * - Calculates the amount of capture using the costs of the items found by IDs in `EventPayment.orderItemIds`.
     * - Adds the expense costs of the entire order to the capture amount if this capture request is the first for the order.
     * - Sends the message using `PaymentConfirmationRequested` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendEventPaymentConfirmationPending(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * Specification:
     * - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     * - Validates if refund process is blocked, throws `EventExecutionForbiddenException` on failure.
     * - Calculates the amount of refund using the costs of the items found by IDs in `EventPayment.orderItemIds`.
     * - Adds the expenses cost of the entire order to refunded amount if this refund request has at least one unreimbursed item left.
     * - Sends the message using `PaymentRefundRequestedTransfer` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendEventPaymentRefundPending(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * Specification:
     * - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     * - Sends the message using `PaymentCancelReservationRequestedTransfer` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendEventPaymentCancelReservationPending(EventPaymentTransfer $eventPaymentTransfer): void;
}
