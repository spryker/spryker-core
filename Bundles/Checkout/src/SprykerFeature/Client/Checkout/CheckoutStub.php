<?php

namespace SprykerFeature\Client\Checkout;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Client\Kernel\AbstractStub;

/**
 * @method CheckoutDependencyContainer getDependencyContainer()
 */
class CheckoutStub extends AbstractStub
{
    /**
     * @param Order $order
     * @return \SprykerFeature\Shared\Library\Communication\Response
     */
    public function saveOrder(Order $order)
    {
        return $this->getDependencyContainer()->createCheckoutManager()->saveOrder($order);
    }

    /**
     * @param Order $order
     * @return Order
     */
    public function clearReferences(Order $order)
    {
        return $this->getDependencyContainer()->createCheckoutManager()->clearReferences($order);
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function setOrderInvalid(Order $order)
    {
        return $this->getDependencyContainer()->createCheckoutManager()->setOrderInvalid($order);
    }
}
