<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentDataTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Payone\Business\PayoneBusinessFactory getFactory()
 */
class PayoneFacade extends AbstractFacade
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createOrderManager()->saveOrder($quoteTransfer);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer
     */
    public function authorizePayment($idPayment)
    {
        return $this->getFactory()->createPaymentManager()->authorizePayment($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this->getFactory()->createPaymentManager()->preAuthorizePayment($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer
     */
    public function capturePayment($idPayment)
    {
        return $this->getFactory()->createPaymentManager()->capturePayment($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer
     */
    public function debitPayment($idPayment)
    {
        return $this->getFactory()->createPaymentManager()->debitPayment($idPayment);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneRefundTransfer $refundTransfer
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer)
    {
        return $this->getFactory()->createPaymentManager()->refundPayment($refundTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneCreditCardTransfer $creditCardData
     *
     * @return \Spryker\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData)
    {
        return $this->getFactory()->createPaymentManager()->creditCardCheck($creditCardData);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return \Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer)
    {
        $transactionManager = $this->getFactory()->createTransactionStatusManager();
        $transactionTransfer = $this->getFactory()->createTransactionStatusUpdateRequest($transactionStatusUpdateTransfer);

        return $transactionManager->processTransactionStatusUpdate($transactionTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isAuthorizationApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationRedirect(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isAuthorizationRedirect($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isPreauthorizationApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationRedirect(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isPreauthorizationRedirect($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationError(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isAuthorizationError($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isCaptureApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureError(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isCaptureError($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isRefundApproved($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundError(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createApiLogFinder()->isRefundError($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createPaymentManager()->isRefundPossible($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createPaymentManager()->isPaymentDataRequired($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PayonePaymentTransfer $payment
     *
     * @return \Generated\Shared\Transfer\PayoneAuthorizationCheckResponseTransfer
     */
    public function getAuthorizationResponse(PayonePaymentTransfer $payment)
    {
        return $this->getFactory()->createApiLogFinder()->getAuthorizationResponse($payment);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentPaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentPaid($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOverpaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentOverpaid($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentAppointed($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentAppointed($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOther($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentOther($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentCapture($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getFactory()
            ->createTransactionStatusManager()
            ->isPaymentCapture($idSalesOrder, $idSalesOrderItem);
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
            ->createPaymentManager()
            ->postSaveHook($quoteTransfer, $checkoutResponse);
    }

    /**
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        return $this->getFactory()->createPaymentManager()->getPaymentLogs($orders);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Generated\Shared\Transfer\PaymentDataTransfer
     */
    public function getPaymentData($idPayment)
    {
        return $this->getFactory()->createPaymentManager()->getPaymentData($idPayment);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentDataTransfer $paymentData
     * @param int $idOrder
     *
     * @return void
     */
    public function updatePaymentDetail(PaymentDataTransfer $paymentData, $idOrder)
    {
        $this->getFactory()->createPaymentManager()->updatePaymentDetail($paymentData, $idOrder);
    }

}
