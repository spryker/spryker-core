<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business;

use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Payone\PayoneCreditCardInterface;
use Generated\Shared\Payone\PayonePaymentInterface;
use Generated\Shared\Payone\PayoneRefundInterface;
use Generated\Shared\Refund\PaymentDataInterface;
use Generated\Shared\Transfer\PaymentDataTransfer;
use Generated\Shared\Transfer\PayoneAuthorizationCheckResponseTransfer;
use Generated\Shared\Payone\OrderInterface;
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
     * @param OrderInterface $orderTransfer
     */
    public function saveOrder(OrderInterface $orderTransfer)
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
     * @param PayoneRefundInterface $refundTransfer
     *
     * @return RefundResponseContainer
     */
    public function refundPayment(PayoneRefundInterface $refundTransfer)
    {
        return $this->getDependencyContainer()->createPaymentManager()->refundPayment($refundTransfer);
    }

    /**
     * @param PayoneCreditCardInterface $creditCardData
     *
     * @return CreditCardCheckResponseContainer
     */
    public function creditCardCheck(PayoneCreditCardInterface $creditCardData)
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
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationRedirect(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isAuthorizationRedirect($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationApproved(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isPreauthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPreauthorizationRedirect(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isPreauthorizationRedirect($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationError(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isAuthorizationError($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isCaptureApproved($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isCaptureError(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isCaptureError($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isRefundApproved($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundError(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isRefundError($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundPossible(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createPaymentManager()->isRefundPossible($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPaymentDataRequired(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createPaymentManager()->isPaymentDataRequired($orderTransfer);
    }

    /**
     * @param PayonePaymentInterface $payment
     *
     * @return PayoneAuthorizationCheckResponseTransfer
     */
    public function getAuthorizationResponse(PayonePaymentInterface $payment)
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
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseInterface $checkoutResponse
     *
     * @return CheckoutResponseInterface
     */
    public function postSaveHook(OrderInterface $orderTransfer, CheckoutResponseInterface $checkoutResponse)
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
     * @return PaymentDataInterface
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
