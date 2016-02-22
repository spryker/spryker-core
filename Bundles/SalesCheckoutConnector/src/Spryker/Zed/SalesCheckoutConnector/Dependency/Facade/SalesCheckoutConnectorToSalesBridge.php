<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Sales\Business\SalesFacade;
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
     * @param \Generated\Shared\Transfer\OrderTransfer $transferOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function saveOrder(OrderTransfer $transferOrder)
    {
        return $this->salesFacade->saveOrder($transferOrder);
    }

}
