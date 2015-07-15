<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business;

use Generated\Shared\Payone\AuthorizationInterface;
use Generated\Shared\Payone\CaptureInterface;
use Generated\Shared\Payone\DebitInterface;
use Generated\Shared\Payone\RefundInterface;
use Generated\Shared\Payone\PayonePaymentInterface;
use Generated\Shared\Payone\CreditCardInterface;
use Generated\Shared\Payone\ApiCallResponseCheckInterface;
use Generated\Shared\Transfer\AuthorizationCheckResponseTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Payone\OrderInterface;
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
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationResponseContainer
     */
    public function authorize(AuthorizationInterface $authorizationData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->authorize($authorizationData);
    }

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationResponseContainer
     */
    public function preAuthorize(AuthorizationInterface $authorizationData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->preAuthorize($authorizationData);
    }

    /**
     * @param CaptureInterface $captureData
     *
     * @return CaptureResponseContainer
     */
    public function capture(CaptureInterface $captureData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->capture($captureData);
    }

    /**
     * @param DebitInterface $debitData
     *
     * @return DebitResponseContainer
     */
    public function debit(DebitInterface $debitData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->debit($debitData);
    }

    /**
     * @param RefundInterface $refundData
     *
     * @return RefundResponseContainer
     */
    public function refund(RefundInterface $refundData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->refund($refundData);
    }

    /**
     * @param CreditCardInterface $creditCardData
     *
     * @return CreditCardCheckResponseContainer
     */
    public function creditCardCheck(CreditCardInterface $creditCardData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->creditCardCheck($creditCardData);
    }

    //@todo type hint right container interface
    /**
     * @param array $requestParams
     *
     * @return TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(array $requestParams)
    {
        $transactionManager = $this->getDependencyContainer()->createTransactionStatusManager();
        $transactionTransfer = $this->getDependencyContainer()->createTransactionStatusUpdateRequest($requestParams);

        return $transactionManager->processTransactionStatusUpdate($transactionTransfer);
    }

    /**
     * @param PayonePaymentInterface $paymentTransfer
     *
     * @return bool
     *
     * @deprecated use is approved & is redirect
     */
    public function isAuthorizationSuccessful(PayonePaymentInterface $paymentTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isAuthorizationSuccessful($paymentTransfer);
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
    public function isPaymentPaid(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isPaymentPaid($orderTransfer);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPaymentUnderPaid(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isPaymentUnderPaid($orderTransfer);
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
     * @return AuthorizationCheckResponseTransfer
     */
    public function getAuthorizationResponse(PayonePaymentInterface $payment)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->getAuthorizationResponse($payment);
    }

    /**
     * @param ApiCallResponseCheckInterface $apiCallCheck
     *
     * @return bool
     */
    public function isApiCallSuccessful(ApiCallResponseCheckInterface $apiCallCheck)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isApiCallSuccessful($apiCallCheck);
    }

    /**
     * @param OrderInterface $orderData
     *
     * @return PaymentStatusTransfer
     */
    public function getPaymentStatus(OrderInterface $orderTransfer)
    {
//        @todo implement
//        return $this->getDependencyContainer()->createTransactionStatusManager()->getPaymentStatus($orderData);
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return PayonePaymentTransfer
     */
    public function getPayment(OrderInterface $orderTransfer)
    {
        return $this->getDependencyContainer()->createPaymentManager()->getPayment($orderTransfer);
    }

}
