<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesCheckoutConnectorToSalesInterface
{

    /**
     * @param OrderTransfer $transferOrder
     *
     * @return OrderTransfer
     */
    public function saveOrder(OrderTransfer $transferOrder);

}
