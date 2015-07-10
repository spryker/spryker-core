<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Payone\CaptureInterface;
use Generated\Shared\Payone\DebitInterface;
use Generated\Shared\Payone\PayonePaymentInterface;
use Generated\Shared\Payone\RefundInterface;
use Generated\Shared\Payone\StandardParameterInterface;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerFeature\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

abstract class AbstractMapper implements PaymentMethodMapperInterface
{

    /**
     * @var StandardParameterInterface
     */
    private $standardParameter;
    /**
     * @var SequenceNumberProviderInterface
     */
    private $sequenceNumberProvider;
    /**
     * @var Store
     */
    protected $storeConfig;

    public function __construct(Store $storeConfig)
    {
        $this->storeConfig = $storeConfig;
    }

    /**
     * @param StandardParameterInterface $standardParameter
     */
    public function setStandardParameter(StandardParameterInterface $standardParameter)
    {
        $this->standardParameter = $standardParameter;
    }

    /**
     * @return StandardParameterInterface
     */
    protected function getStandardParameter()
    {
        return $this->standardParameter;
    }

    /**
     * @param SequenceNumberProviderInterface $sequenceNumberProvider
     */
    public function setSequenceNumberProvider(SequenceNumberProviderInterface $sequenceNumberProvider)
    {
        $this->sequenceNumberProvider = $sequenceNumberProvider;
    }

    /**
     * @return SequenceNumberProviderInterface
     */
    protected function getSequenceNumberProvider()
    {
        return $this->sequenceNumberProvider;
    }

    /**
     * @param PayonePaymentInterface $payment
     *
     * @return int
     */
    protected function getNextSequenceNumber(PayonePaymentInterface $payment)
    {
        $transactionId = $payment->getTransactionId();
        $nextSequenceNumber = $this->getSequenceNumberProvider()->getNextSequenceNumber($transactionId);

        return $nextSequenceNumber;
    }

    /**
     * @param CaptureInterface $captureData
     *
     * @return CaptureContainer
     */
    public function mapCapture(CaptureInterface $captureData)
    {
        $captureContainer = new CaptureContainer();

        $captureContainer->setTxid($captureData->getPayment()->getTransactionId());
        $captureContainer->setSequenceNumber($this->getNextSequenceNumber($captureData->getPayment()));
        $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $captureContainer->setAmount($captureData->getAmount());

        return $captureContainer;
    }

    /**
     * @param DebitInterface $debitData
     *
     * @return DebitContainer
     */
    public function mapDebit(DebitInterface $debitData)
    {
        $debitContainer = new DebitContainer();

        $debitContainer->setTxid($debitData->getPayment()->getTransactionId());
        $debitContainer->setSequenceNumber($this->getNextSequenceNumber($debitData->getPayment()));
        $debitContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $debitContainer->setAmount($debitData->getAmount());

        return $debitContainer;

    }

    /**
     * @param RefundInterface $refundData
     *
     * @return RefundContainer
     */
    public function mapRefund(RefundInterface $refundData)
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($refundData->getPayment()->getTransactionId());
        $refundContainer->setSequenceNumber($this->getNextSequenceNumber($refundData->getPayment()));
        $refundContainer->setAmount($refundData->getAmount());
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $refundContainer->setNarrativeText($refundData->getNarrativeText());
        $refundContainer->setUseCustomerData($refundData->getUseCustomerdata());

        return $refundContainer;
    }

}
