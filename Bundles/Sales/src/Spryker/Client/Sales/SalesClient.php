<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Sales;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class SalesClient extends AbstractClient implements SalesClientInterface
{

    /**
     * @param OrderListTransfer $orderListTransfer
     *
     * @return OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedSalesStub()
            ->getOrders($orderListTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedSalesStub()
            ->getOrderDetails($orderTransfer);
    }

}
