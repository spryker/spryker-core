<?php

namespace SprykerFeature\Shared\Payone\Transfer;


use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

abstract class Transaction extends AbstractTransfer implements TransactionInterface
{

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var string
     */
    protected $paymentMethod;


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