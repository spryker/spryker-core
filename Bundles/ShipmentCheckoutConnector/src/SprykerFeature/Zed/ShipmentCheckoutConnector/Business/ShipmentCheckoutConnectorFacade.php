<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ShipmentCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class ShipmentCheckoutConnectorFacade extends AbstractFacade
{

    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $this->getDependencyContainer()->createShipmentOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

    public function saveShipmentForOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->createShipmentOrderSaver()->saveShipmentForOrder($orderTransfer, $checkoutResponse);
    }

}
