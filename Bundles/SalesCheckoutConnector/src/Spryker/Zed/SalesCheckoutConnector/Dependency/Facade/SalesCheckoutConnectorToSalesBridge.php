<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

class SalesCheckoutConnectorToSalesBridge implements SalesCheckoutConnectorToSalesInterface
{

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacade
     */
    protected $salesFacade;

    /**
     * SalesCheckoutConnectorToSalesBridge constructor.
     *
     * @param \Spryker\Zed\Sales\Business\SalesFacade $salesFacade
     */
    public function __construct($salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param OrderTransfer $transferOrder
     *
     * @return OrderTransfer
     */
    public function saveOrder(OrderTransfer $transferOrder)
    {
        return $this->salesFacade->saveOrder($transferOrder);
    }

}
