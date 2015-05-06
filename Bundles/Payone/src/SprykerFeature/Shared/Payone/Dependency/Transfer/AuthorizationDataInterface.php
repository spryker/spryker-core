<?php

namespace SprykerFeature\Shared\Payone\Dependency\Transfer;


interface AuthorizationDataInterface
{

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod);

    /**
     * @return string
     */
    public function getPaymentMethod();

    /**
     * @return string
     */
    public function getReferenceId();

    /**
     * @param string $referenceId
     */
    public function setReferenceId($referenceId);

    /**
     * @return PaymentUserDataInterface
     */
    public function getPaymentUserData();

    /**
     * @param PaymentUserDataInterface $paymentFormData
     */
    public function setPaymentUserData(PaymentUserDataInterface $paymentFormData);

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param $amount
     */
    public function setAmount($amount);

    /**
     * @return OrderInterface   // FIXME needs refactoring of Sales package
     */
    public function getOrder();

    /**
     * @param OrderInterface $order // FIXME needs refactoring of Sales package
     */
    public function setOrder($order);

}