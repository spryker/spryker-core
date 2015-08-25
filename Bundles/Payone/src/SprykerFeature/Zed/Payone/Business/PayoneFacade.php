<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business;

use Generated\Shared\Payone\PayoneCreditCardInterface;
use Generated\Shared\Payone\PayonePaymentInterface;
use Generated\Shared\Payone\PayoneRefundInterface;
use Generated\Shared\Transfer\PayoneAuthorizationCheckResponseTransfer;
use Generated\Shared\Payone\OrderInterface;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
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
     * @param OrderInterface $orderData
     */
    public function saveOrder(OrderInterface $orderData)
    {
        $this->getDependencyContainer()->createOrderManager()->saveOrder($orderData);
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
     * @param PayonePaymentInterface $payment
     *
     * @return PayoneAuthorizationCheckResponseTransfer
     */
    public function getAuthorizationResponse(PayonePaymentInterface $payment)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->getAuthorizationResponse($payment);
    }

    /**
     * @param $salesOrderId
     * @param $salesOrderItemId
     * @return bool
     */
    public function isPaymentNotificationAvailable($salesOrderId, $salesOrderItemId)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentNotificationAvailable($salesOrderId, $salesOrderItemId);
    }

    public function isPaymentPaid($salesOrderId, $salesOrderItemId) {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentPaid($salesOrderId, $salesOrderItemId);
    }

    public function isPaymentOverpaid($salesOrderId, $salesOrderItemId) {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentOverpaid($salesOrderId, $salesOrderItemId);
    }

    public function isPaymentUnderpaid($salesOrderId, $salesOrderItemId) {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentUnderpaid($salesOrderId, $salesOrderItemId);
    }

    public function isPaymentAppointed($salesOrderId, $salesOrderItemId)
    {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentAppointed($salesOrderId, $salesOrderItemId);
    }

    public function isPaymentOther($salesOrderId, $salesOrderItemId) {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentOther($salesOrderId, $salesOrderItemId);
    }

    public function isPaymentCapture($salesOrderId, $salesOrderItemId) {
        return $this->getDependencyContainer()
            ->createTransactionStatusManager()
            ->isPaymentCapture($salesOrderId, $salesOrderItemId);
    }

}
