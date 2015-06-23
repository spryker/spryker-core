<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Checkout;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Sdk\Kernel\AbstractSdk;

/**
 * @method CheckoutDependencyContainer getDependencyContainer()
 */
class CheckoutSdk extends AbstractSdk
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
