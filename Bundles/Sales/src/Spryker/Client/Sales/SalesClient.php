<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Sales;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Sales\SalesFactory getFactory()
 */
class SalesClient extends AbstractClient implements SalesClientInterface
{

    /**
     * Returns the sales orders for the given customer and filters
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer)
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getOrders($orderListTransfer);
    }

    /**
     * Returns details for the given order id
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getOrderDetails($orderTransfer);
    }

}
