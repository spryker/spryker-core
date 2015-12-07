<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerFeature\Zed\SalesCheckoutConnector\Dependency\Facade\SalesCheckoutConnectorToSalesInterface;

class SalesOrderSaver implements SalesOrderSaverInterface
{

    /**
     * @var SalesCheckoutConnectorToSalesInterface
     */
    protected $salesFacade;

    /**
     * @param SalesCheckoutConnectorToSalesInterface $salesFacade
     */
    public function __construct(SalesCheckoutConnectorToSalesInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param OrderTransfer $order
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(OrderTransfer $order, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->salesFacade->saveOrder($order);
    }

}
