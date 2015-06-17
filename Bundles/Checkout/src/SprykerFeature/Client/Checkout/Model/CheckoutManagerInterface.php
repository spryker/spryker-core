<?php

namespace SprykerFeature\Client\Checkout\Model;

use SprykerFeature\Shared\Library\Communication\Response;
use Generated\Shared\Transfer\OrderTransfer;

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
