<?php

namespace SprykerFeature\Shared\Payone\Transfer;


interface TransactionInterface
{

    /**
     * @return OrderInterface   // FIXME needs refactoring of Sales package
     */
    public function getOrder();

    /**
     * @param OrderInterface $order  // FIXME needs refactoring of Sales package
     */
    public function setOrder($order);

    /**
     * @return string
     */
    public function getPaymentMethod();

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod);

}