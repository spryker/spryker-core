<?php

namespace SprykerFeature\Shared\Payone\Transfer;


interface AuthorizationDataInterface
{

    /**
     * @return string
     */
    public function getReferenceId();

    /**
     * @param string $referenceId
     */
    public function setReferenceId($referenceId);

    /**
     * @return PaymentInterface   // FIXME needs refactoring of Sales package
     */
    public function getPayment();

    /**
     * @return PaymentInterface   // FIXME needs refactoring of Sales package
     */
    public function setPayment($payment);

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