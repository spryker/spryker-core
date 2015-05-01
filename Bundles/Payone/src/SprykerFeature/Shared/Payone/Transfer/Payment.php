<?php

namespace SprykerFeature\Shared\Payone\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;


class Payment extends AbstractTransfer implements PaymentInterface
{

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var string
     */
    protected $paymentMethod;


    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

}