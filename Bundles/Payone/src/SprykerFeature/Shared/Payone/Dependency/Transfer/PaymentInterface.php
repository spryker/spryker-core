<?php

namespace SprykerFeature\Shared\Payone\Dependency\Transfer;


interface PaymentInterface
{

    /**
     * @return mixed
     */
    public function getTransactionId();

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId);

    /**
     * @return string
     */
    public function getPaymentMethod();

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod);

    /**
     * @return string
     */
    public function getAuthorizationType();

    /**
     * @param string $paymentMethod
     */
    public function setAuthorizationType($paymentMethod);

}