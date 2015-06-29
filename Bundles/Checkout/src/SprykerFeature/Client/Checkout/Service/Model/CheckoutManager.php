<?php

namespace SprykerFeature\Client\Checkout\Service\Model;

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
     * @param OrderTransfer $order
     * @return Response
     */
    public function saveOrder(OrderTransfer $order)
    {
        $this->zedClient->call('checkout/gateway/save-order', $order, 60);

        return $this->zedClient->getLastResponse();
    }

    /**
     * @param OrderTransfer $order
     *
     * @return OrderTransfer
     */
    public function clearReferences(OrderTransfer $order)
    {
        $order->setIdSalesOrder(null);
        $order->setIncrementId(null);

        return $order;
    }
}
