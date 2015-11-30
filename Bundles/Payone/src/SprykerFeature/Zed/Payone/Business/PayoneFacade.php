<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\PaymentDataTransfer;
use Generated\Shared\Transfer\PayoneAuthorizationCheckResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CreditCardCheckResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class PayoneFacade extends AbstractFacade
{

    /**
     * @param OrderTransfer $orderTransfer
     */
    public function saveOrder(OrderTransfer $orderTransfer)
    {
        $this->getDependencyContainer()->createOrderManager()->saveOrder($orderTransfer);
    }

    /**
     * @param int $idPayment
     *
     * @return AuthorizationResponseContainer
     */
    public function authorizePayment($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->authorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return AuthorizationResponseContainer
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->preAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return CaptureResponseContainer
     */
    public function capturePayment($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->capturePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return DebitResponseContainer
     */
    public function debitPayment($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->debitPayment($idPayment);
    }

    /**
     * @param PayoneRefundTransfer $refundTransfer
     *
     * @return RefundResponseContainer
     */
    public function refundPayment(PayoneRefundTransfer $refundTransfer)
    {
        return $this->getDependencyContainer()->createPaymentManager()->refundPayment($refundTransfer);
    }

    /**
     * @param PayoneCreditCardTransfer $creditCardData
     *
     * @return CreditCardCheckResponseContainer
     */
    public function creditCardCheck(PayoneCreditCardTransfer $creditCardData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->creditCardCheck($creditCardData);
    }

    /**
     * @param PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer)
    {
        $transactionManager = $this->getDependencyContainer()->createTransactionStatusManager();
        $transactionTransfer = $this->getDependencyContainer()->createTransactionStatusUpdateRequest($transactionStatusUpdateTransfer);

        return $transactionManager->processTransactionStatusUpdate($transactionTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationRedirect(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isAuthorizationRedirect($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isPreauthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationRedirect(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isPreauthorizationRedirect($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationError(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isAuthorizationError($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isCaptureApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureError(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isCaptureError($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isRefundApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundError(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isRefundError($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createPaymentManager()->isRefundPossible($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createPaymentManager()->isPaymentDataRequired($orderTransfer);
    }

    /**
     * @param PayonePaymentTransfer $payment
     *
     * @return PayoneAuthorizationCheckResponseTransfer
     */
    public function getAuthorizationResponse(PayonePaymentTransfer $payment)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->getAuthorizationResponse($payment);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentNotificationAvailable($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentPaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentPaid($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOverpaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentOverpaid($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentUnderpaid($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentAppointed($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentAppointed($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOther($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentOther($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentCapture($idSalesOrder, $idSalesOrderItem)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentCapture($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return CheckoutResponseTransfer
     */
    public function postSaveHook(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        return $this->getDependencyContainer()
            ->createPaymentManager()
            ->postSaveHook($orderTransfer, $checkoutResponse);
    }

    /**
     * @param ObjectCollection $orders
     *
     * @return PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        return $this->getDependencyContainer()->createPaymentManager()->getPaymentLogs($orders);
    }

    /**
     * @param int $idPayment
     *
     * @return PaymentDataTransfer
     */
    public function getPaymentData($idPayment)
    {
        return $this->getDependencyContainer()->createPaymentManager()->getPaymentData($idPayment);
    }

    /**
     * @param PaymentDataTransfer $paymentData
     * @param int $idOrder
     */
    public function updatePaymentDetail(PaymentDataTransfer $paymentData, $idOrder)
    {
        $this->getDependencyContainer()->createPaymentManager()->updatePaymentDetail($paymentData, $idOrder);
    }

}
