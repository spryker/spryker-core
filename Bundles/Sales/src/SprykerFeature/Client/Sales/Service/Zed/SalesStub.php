<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Sales\Service\Zed;

use Generated\Shared\Sales\OrderListInterface;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class SalesStub implements SalesStubInterface
{

    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param OrderListInterface $orderListTransfer
     * 
     * @return OrderListInterface
     */
    public function getOrders(OrderListInterface $orderListTransfer)
    {
        return $this->zedStub->call('/sales/gateway/get-orders', $orderListTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        return $this->zedStub->call('/sales/gateway/get-order-details', $orderTransfer);
    }

}
