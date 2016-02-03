<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\SalesCheckoutConnector\Dependency\Facade\SalesCheckoutConnectorToSalesInterface;

class SalesOrderSaver implements SalesOrderSaverInterface
{

    /**
     * @var \Spryker\Zed\SalesCheckoutConnector\Dependency\Facade\SalesCheckoutConnectorToSalesInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesCheckoutConnector\Dependency\Facade\SalesCheckoutConnectorToSalesInterface $salesFacade
     */
    public function __construct(SalesCheckoutConnectorToSalesInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $order
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(OrderTransfer $order, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->salesFacade->saveOrder($order);
    }

}
