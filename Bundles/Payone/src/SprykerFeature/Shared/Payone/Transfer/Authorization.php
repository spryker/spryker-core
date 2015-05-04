<?php

namespace SprykerFeature\Shared\Payone\Transfer;


use SprykerFeature\Shared\Payone\Dependency\AuthorizationDataInterface;
use SprykerFeature\Shared\Payone\Dependency\PaymentUserDataInterface;

class Authorization implements AuthorizationDataInterface
{

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var PaymentUserDataInterface
     */
    protected $paymentFormData;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $referenceId;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @param string $method
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return PaymentUserDataInterface
     */
    public function getPaymentUserData()
    {
        return $this->paymentFormData;
    }

    /**
     * @param PaymentUserDataInterface $paymentFormData
     */
    public function setPaymentUserData(PaymentUserDataInterface $paymentFormData)
    {
        $this->paymentFormData = $paymentFormData;
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

    /**
     * @return OrderInterface   // FIXME needs refactoring of Sales package
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param OrderInterface $order // FIXME needs refactoring of Sales package
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

}