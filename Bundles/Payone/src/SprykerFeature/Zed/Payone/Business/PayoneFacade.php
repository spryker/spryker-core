<?php

namespace SprykerFeature\Zed\Payone\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Payone\Dependency\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Dependency\CaptureDataInterface;
use SprykerFeature\Shared\Payone\Dependency\DebitDataInterface;
use SprykerFeature\Shared\Payone\Dependency\RefundDataInterface;


/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class PayoneFacade extends AbstractFacade
{

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return Api\Response\Container\AuthorizationResponseContainer
     */
    public function authorize(AuthorizationDataInterface $authorizationData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->authorize($authorizationData);
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return Api\Response\Container\AuthorizationResponseContainer
     */
    public function preAuthorize(AuthorizationDataInterface $authorizationData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->preAuthorize($authorizationData);
    }

    /**
     * @param CaptureDataInterface $captureData
     * @return Api\Response\Container\CaptureResponseContainer
     */
    public function capture(CaptureDataInterface $captureData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->capture($captureData);
    }

    /**
     * @param DebitDataInterface $debitData
     * @return Api\Response\Container\DebitResponseContainer
     */
    public function debit(DebitDataInterface $debitData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->debit($debitData);
    }

    /**
     * @param RefundDataInterface $refundData
     * @return Api\Response\Container\RefundResponseContainer
     */
    public function refund(RefundDataInterface $refundData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager();

        return $paymentManager->refund($refundData);
    }

    /**
     * @todo: what do we pass here as facade method param?
     * @param array $requestParams
     * @return mixed
     */
    public function processTransactionStatusUpdate(array $requestParams)
    {
        $transactionManager = $this->getDependencyContainer()->createTransactionStatusManager();

        return $transactionManager->processTransactionStatusUpdate($requestParams);
    }

}
