<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesCheckoutConnector\Business\SalesCheckoutConnectorBusinessFactory getFactory()
 */
class SalesCheckoutConnectorFacade extends AbstractFacade implements SalesCheckoutConnectorFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createSalesOrderSaver()->saveOrder($orderTransfer, $checkoutResponse);
    }

}
