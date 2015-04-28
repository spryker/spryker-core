<?php

namespace SprykerFeature\Shared\Payone\Transfer;


interface CaptureDataInterface extends TransactionInterface
{

    /**
     * @return PaymentInterface
     */
    public function getPayment();

    /**
     * @param PaymentInterface $payment
     */
    public function setPayment(PaymentInterface $payment);

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param $amount
     */
    public function setAmount($amount);

}