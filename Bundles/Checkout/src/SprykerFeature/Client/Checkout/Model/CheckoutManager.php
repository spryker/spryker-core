<?php

namespace SprykerFeature\Client\Checkout\Model;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Shared\Library\Communication\Response;
use SprykerFeature\Shared\ZedRequest\Client\AbstractZedClient;

class CheckoutManager implements CheckoutManagerInterface
{
    /**
     * @var AbstractZedClient
     */
    protected $zedClient;

    /**
     * @param AbstractZedClient $zedClient
     */
    public function __construct(AbstractZedClient $zedClient)
    {
        $this->zedClient = $zedClient;
    }

    /**
     * @param Order $order
     * @return Response
     */
    public function saveOrder(Order $order)
    {
        $this->zedClient->call('checkout/client/save-order', $order, 60);

        return $this->zedClient->getLastResponse();
    }

    /**
     * @param Order $order
     * @return Order
     */
    public function clearReferences(Order $order)
    {
        $order->setIdSalesOrder(null);
        $order->setIncrementId(null);

        return $order;
    }
}
