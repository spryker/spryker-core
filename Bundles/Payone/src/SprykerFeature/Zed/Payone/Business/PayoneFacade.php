<?php

namespace SprykerFeature\Zed\Payone\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Payone\Dependency\Transfer\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Dependency\Transfer\CaptureDataInterface;
use SprykerFeature\Shared\Payone\Dependency\Transfer\DebitDataInterface;
use SprykerFeature\Shared\Payone\Dependency\Transfer\RefundDataInterface;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse;


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
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->authorize($authorizationData);
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AuthorizationResponseContainer
     */
    public function preAuthorize(AuthorizationDataInterface $authorizationData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->preAuthorize($authorizationData);
    }

    /**
     * @param CaptureDataInterface $captureData
     * @return CaptureResponseContainer
     */
    public function capture(CaptureDataInterface $captureData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->capture($captureData);
    }

    /**
     * @param DebitDataInterface $debitData
     * @return DebitResponseContainer
     */
    public function debit(DebitDataInterface $debitData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->debit($debitData);
    }

    /**
     * @param RefundDataInterface $refundData
     * @return RefundResponseContainer
     */
    public function refund(RefundDataInterface $refundData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->refund($refundData);
    }

    /**
     * @param array $requestParams
     * @return TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(array $requestParams)
    {
        $transactionManager = $this->getDependencyContainer()->createTransactionStatusManager();

        return $transactionManager->processTransactionStatusUpdate($requestParams);
    }

}
