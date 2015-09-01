<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Sales\Service;

use Generated\Shared\Sales\OrderListInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method SalesDependencyContainer getDependencyContainer()
 */
class SalesClient extends AbstractClient implements SalesClientInterface
{

    /**
     * @param OrderListInterface $orderListTransfer
     * 
     * @return OrderListInterface
     */
    public function getOrders(OrderListInterface $orderListTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedSalesStub()
            ->getOrders($orderListTransfer)
        ;
    }
}
