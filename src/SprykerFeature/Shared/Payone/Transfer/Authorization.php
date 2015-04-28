<?php

namespace SprykerFeature\Shared\Payone\Transfer;


class Authorization extends Transaction implements AuthorizationDataInterface
{

    /**
     * @var PaymentFormDataInterface
     */
    protected $payment;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $referenceId;


    /**
     * @return PaymentFormDataInterface   // FIXME needs refactoring of Sales package or better TALK ABOUT IT
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return PaymentFormDataInterface   // FIXME needs refactoring of Sales package or better TALK ABOUT IT
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * @param string $referenceId
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;
    }

}