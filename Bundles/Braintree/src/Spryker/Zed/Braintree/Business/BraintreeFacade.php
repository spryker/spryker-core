<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TransactionMetaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Braintree\Business\BraintreeBusinessFactory getFactory()
 */
class BraintreeFacade extends AbstractFacade implements BraintreeFacadeInterface
{
    /**
     * Specification:
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this
             ->getFactory()
             ->createOrderSaver()
             ->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * Specification:
     * - Sends pre-authorize payment request to Braintree gateway to retrieve transaction data.
     * - Checks that form data matches transaction response data
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function preCheckPayment(QuoteTransfer $quoteTransfer)
    {
        return $this
            ->getFactory()
            ->createPreCheckTransactionHandler()
            ->preCheck($quoteTransfer);
    }

    /**
     * Specification:
     * - Processes payment confirmation request to Braintree gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TransactionMetaTransfer $transactionMetaTransfer
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function authorizePayment(TransactionMetaTransfer $transactionMetaTransfer)
    {
        return $this
            ->getFactory()
            ->createAuthorizeTransactionHandler()
            ->authorize($transactionMetaTransfer);
    }

    /**
     * Specification:
     * - Processes capture payment request to Braintree gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TransactionMetaTransfer $transactionMetaTransfer
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function capturePayment(TransactionMetaTransfer $transactionMetaTransfer)
    {
        return $this
            ->getFactory()
            ->createCaptureTransactionHandler()
            ->capture($transactionMetaTransfer);
    }

    /**
     * Specification:
     * - Processes cancel payment request to Braintree gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TransactionMetaTransfer $transactionMetaTransfer
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function revertPayment(TransactionMetaTransfer $transactionMetaTransfer)
    {
        return $this
            ->getFactory()
            ->createRevertTransactionHandler()
            ->revert($transactionMetaTransfer);
    }

    /**
     * Specification:
     * - Calculate RefundTransfer for given $salesOrderItems and $salesOrderEntity.
     * - Processes refund request to Braintree gateway by calculated RefundTransfer.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function refundPayment(array $salesOrderItems, SpySalesOrder $salesOrderEntity)
    {
        return $this
            ->getFactory()
            ->createRefundTransactionHandler()
            ->refund($salesOrderItems, $salesOrderEntity);
    }

    /**
     * Specification:
     * - Checks if pre-authorization API request got success response from Braintree gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isAuthorizationApproved($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if cancel API request got success response from Braintree gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isReversalApproved($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if capture API request got success response from Braintree gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isCaptureApproved($orderTransfer);
    }

    /**
     * Specification:
     * - Checks if refund API request got success response from Braintree gateway.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getFactory()
            ->createTransactionStatusLog()
            ->isRefundApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function postSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        return $this->getFactory()
            ->createPostSaveHook()
            ->postSaveHook($quoteTransfer, $checkoutResponse);
    }
}
