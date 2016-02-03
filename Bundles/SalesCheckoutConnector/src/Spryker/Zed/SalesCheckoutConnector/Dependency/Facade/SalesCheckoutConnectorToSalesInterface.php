<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesCheckoutConnectorToSalesInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $transferOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function saveOrder(OrderTransfer $transferOrder);

}
