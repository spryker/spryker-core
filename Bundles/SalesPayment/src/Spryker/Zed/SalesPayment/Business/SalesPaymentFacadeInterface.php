<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business;

use Generated\Shared\Transfer\EventPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentCriteriaTransfer;
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
     *  - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     *  - Validates if capturing process can be executed, throws `EventExecutionForbiddenException` on failure.
     *  - Calculates the amount of capture using the costs of the items found by IDs in `EventPayment.orderItemIds`.
     *  - Adds the expense costs of the entire order to the capture amount if this capture request is the first for the order.
     *  - Sends the message using `CapturePayment` transfer.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface::sendCapturePaymentMessage()} instead.
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
     * - Sends the message using `RefundPaymentTransfer` transfer.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface::sendRefundPaymentMessage()} instead.
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendEventPaymentRefundPending(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * Specification:
     * - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     * - Sends the message using `CancelPaymentTransfer` transfer.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface::sendCancelPaymentMessage()} instead.
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendEventPaymentCancelReservationPending(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * Specification:
     *  - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     *  - Validates if capturing process can be executed, throws `EventExecutionForbiddenException` on failure.
     *  - Calculates the amount of capture using the costs of the items found by IDs in `EventPayment.orderItemIds`.
     *  - Adds the expense costs of the entire order to the capture amount if this capture request is the first for the order.
     *  - Sends the message using `CapturePayment` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendCapturePaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * Specification:
     * - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     * - Sends the message using `CancelPaymentTransfer` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendCancelPaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * Specification:
     * - Attempts to find an existing order using `EventPayment.IdSalesOrder`, throws `OrderNotFoundException` on failure.
     * - Validates if refund process is blocked, throws `EventExecutionForbiddenException` on failure.
     * - Calculates the amount of refund using the costs of the items found by IDs in `EventPayment.orderItemIds`.
     * - Adds the expenses cost of the entire order to refunded amount if this refund request has at least one unreimbursed item left.
     * - Sends the message using `RefundPaymentTransfer` transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventPaymentTransfer $eventPaymentTransfer
     *
     * @return void
     */
    public function sendRefundPaymentMessage(EventPaymentTransfer $eventPaymentTransfer): void;

    /**
     * Specification:
     * - Returns a `SalesPaymentCollectionTransfer` with `SalesPaymentTransfers` filtered by the provided criteria.
     * - Requires `SalesPaymentCollectionTransfer::ID_SALES_ORDER` to be set.
     * - Returns an empty `SalesPaymentCollectionTransfer` if no payments are found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentCollectionTransfer
     */
    public function getSalesPaymentCollection(SalesPaymentCriteriaTransfer $salesPaymentCriteriaTransfer): SalesPaymentCollectionTransfer;
}
