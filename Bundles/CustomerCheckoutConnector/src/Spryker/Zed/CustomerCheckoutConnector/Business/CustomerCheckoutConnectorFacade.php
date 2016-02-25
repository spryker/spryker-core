<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerCheckoutConnector\Business\CustomerCheckoutConnectorBusinessFactory getFactory()
 */
class CustomerCheckoutConnectorFacade extends AbstractFacade implements CustomerCheckoutConnectorFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $order
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     *
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $this->getFactory()->createCustomerOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

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
        $this->getFactory()->createCustomerOrderSaver()->saveOrder($orderTransfer, $checkoutResponse);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkPreConditions(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createPreConditionChecker()->checkPreConditions($checkoutRequest, $checkoutResponse);
    }

}
