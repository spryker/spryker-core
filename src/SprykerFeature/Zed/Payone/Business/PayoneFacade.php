<?php

namespace SprykerFeature\Zed\Payone\Business;

use SprykerFeature\Shared\Payone\Transfer\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Transfer\CaptureDataInterface;
use SprykerFeature\Shared\Payone\Transfer\DebitDataInterface;
use SprykerFeature\Shared\Payone\Transfer\RefundDataInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;


/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class PayoneFacade extends AbstractFacade
{

    /**
     * @param AuthorizationDataInterface $authorizationData
     */
    public function authorize(AuthorizationDataInterface $authorizationData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager($authorizationData->getPaymentMethod());

        $paymentManager->authorize($authorizationData);
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     */
    public function preAuthorize(AuthorizationDataInterface $authorizationData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager($authorizationData->getPaymentMethod());

        $paymentManager->preAuthorize($authorizationData);
    }

    /**
     * @param CaptureDataInterface $captureData
     */
    public function capture(CaptureDataInterface $captureData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager($captureData->getPayment()->getPaymentMethod());

        $paymentManager->capture($captureData);
    }

    /**
     * @param DebitDataInterface $debitData
     */
    public function debit(DebitDataInterface $debitData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager($debitData->getPayment()->getPaymentMethod());

        $paymentManager->debit($debitData);
    }

    /**
     * @param RefundDataInterface $refundData
     */
    public function refund(RefundDataInterface $refundData)
    {
        $paymentManager = $this->getDependencyContainer()->createPaymentManager($refundData->getPayment()->getPaymentMethod());

        $paymentManager->refund($refundData);
    }

    public function myTest()
    {
        die('motherfucker');
    }

}
