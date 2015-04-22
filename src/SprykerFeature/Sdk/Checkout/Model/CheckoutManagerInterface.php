<?php

namespace SprykerFeature\Sdk\Checkout\Model;

use SprykerFeature\Shared\Library\Communication\Response;
use SprykerFeature\Shared\Sales\Transfer\Order;

interface CheckoutManagerInterface
{
    /**
     * @param Order $order
     * @return Response
     */
    public function saveOrder(Order $order);

    /**
     * @param Order $order
     * @return Order
     */
    public function clearReferences(Order $order);
}
