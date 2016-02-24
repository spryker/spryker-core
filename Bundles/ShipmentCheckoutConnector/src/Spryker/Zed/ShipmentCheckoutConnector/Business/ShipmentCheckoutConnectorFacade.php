<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShipmentCheckoutConnector\Business\ShipmentCheckoutConnectorBusinessFactory getFactory()
 */
class ShipmentCheckoutConnectorFacade extends AbstractFacade implements ShipmentCheckoutConnectorFacadeInterface
{

    /**
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $this->getFactory()->createShipmentOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

    /**
     * @return void
     */
    public function saveShipmentForOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createShipmentOrderSaver()->saveShipmentForOrder($orderTransfer, $checkoutResponse);
    }

}
