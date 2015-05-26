<?php

namespace SprykerFeature\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Payone\CaptureInterface;
use Generated\Shared\Payone\DebitInterface;
use Generated\Shared\Payone\RefundInterface;
use Generated\Shared\Payone\StandardParameterInterface;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerFeature\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;

abstract class AbstractMapper implements PaymentMethodMapperInterface, PayoneApiConstants
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
     * @param CaptureInterface $captureData
     * @return CaptureContainer
     */
    public function mapCapture(CaptureInterface $captureData)
    {
        $captureContainer = new CaptureContainer();

        $captureContainer->setTxid($captureData->getPayment()->getTransactionId());
        $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $captureContainer->setAmount($captureData->getAmount());
        $captureContainer->setSequenceNumber(
            $this->getSequenceNumberProvider()->getNextSequenceNumber($captureData->getPayment()->getTransactionId())
        );

        return $captureContainer;
    }

    /**
     * @param DebitInterface $debitData
     * @return DebitContainer
     */
    public function mapDebit(DebitInterface $debitData)
    {
        $debitContainer = new DebitContainer();

        $debitContainer->setTxid($debitData->getPayment()->getTransactionId());
        $debitContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $debitContainer->setAmount($debitData->getAmount());
        $debitContainer->setSequenceNumber(
            $this->getSequenceNumberProvider()->getNextSequenceNumber($debitData->getPayment()->getTransactionId())
        );

        return $debitContainer;
    }

    /**
     * @param RefundInterface $refundData
     * @return RefundContainer
     */
    public function mapRefund(RefundInterface $refundData)
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($refundData->getPayment()->getTransactionId());
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $refundContainer->setAmount($refundData->getAmount());
        $refundContainer->setSequenceNumber(
            $this->getSequenceNumberProvider()->getNextSequenceNumber($refundData->getPayment()->getTransactionId())
        );

        return $refundContainer;
    }

}
