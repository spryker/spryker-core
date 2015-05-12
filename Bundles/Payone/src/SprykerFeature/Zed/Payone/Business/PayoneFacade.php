<?php

namespace SprykerFeature\Zed\Payone\Business;

use Generated\Shared\Transfer\PayoneApiCallResponseCheckTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Payone\Dependency\Transfer\ApiCallResponseCheckInterface;
use SprykerFeature\Shared\Payone\Dependency\Transfer\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Dependency\Transfer\CaptureDataInterface;
use SprykerFeature\Shared\Payone\Dependency\Transfer\DebitDataInterface;
use SprykerFeature\Shared\Payone\Dependency\Transfer\PaymentInterface;
use SprykerFeature\Shared\Payone\Dependency\Transfer\RefundDataInterface;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse;
use Generated\Shared\Transfer\PayoneAuthorizationCheckResponseTransfer;


/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class PayoneFacade extends AbstractFacade
{

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AuthorizationResponseContainer
     */
    public function authorize(AuthorizationDataInterface $authorizationData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->authorize($authorizationData);
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AuthorizationResponseContainer
     */
    public function preAuthorize(AuthorizationDataInterface $authorizationData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->preAuthorize($authorizationData);
    }

    /**
     * @param CaptureDataInterface $captureData
     * @return CaptureResponseContainer
     */
    public function capture(CaptureDataInterface $captureData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->capture($captureData);
    }

    /**
     * @param DebitDataInterface $debitData
     * @return DebitResponseContainer
     */
    public function debit(DebitDataInterface $debitData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->debit($debitData);
    }

    /**
     * @param RefundDataInterface $refundData
     * @return RefundResponseContainer
     */
    public function refund(RefundDataInterface $refundData)
    {
        return $this->getDependencyContainer()->createPaymentManager()->refund($refundData);
    }

    /**
     * @param array $requestParams
     * @return TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(array $requestParams)
    {
        $transactionManager = $this->getDependencyContainer()->createTransactionStatusManager();
        $transactionTransfer = $this->getDependencyContainer()->createTransactionStatusUpdateRequest($requestParams);

        return $transactionManager->processTransactionStatusUpdate($transactionTransfer);
    }

    /**
     * @param PaymentInterface $payment
     * @return bool
     */
    public function isAuthorizationSuccessful(PaymentInterface $payment)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isAuthorizationSuccessful($payment);
    }

    /**
     * @param PaymentInterface $payment
     * @return PayoneAuthorizationCheckResponseTransfer
     */
    public function getAuthorizationResponse(PaymentInterface $payment)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->getAuthorizationResponse($payment);
    }

    /**
     * @param ApiCallResponseCheckInterface $apiCallCheck
     * @return bool
     */
    public function isApiCallSuccessful(ApiCallResponseCheckInterface $apiCallCheck)
    {
        return $this->getDependencyContainer()->createApiLogFinder()->isApiCallSuccessful($apiCallCheck);
    }

}
