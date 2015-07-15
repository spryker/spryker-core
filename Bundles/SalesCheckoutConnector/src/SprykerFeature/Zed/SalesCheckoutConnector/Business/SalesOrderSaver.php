<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Sales\OrderInterface;
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
     * @param OrderInterface $order
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveOrder(OrderInterface $order, CheckoutResponseTransfer $checkoutResponse)
    {
         $this->salesFacade->saveOrder($order);
    }

}
