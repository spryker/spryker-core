<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector\Business;

use Generated\Shared\ShipmentCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\ShipmentCheckoutConnector\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ShipmentCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class ShipmentCheckoutConnectorFacade extends AbstractFacade
{

    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request)
    {
        $this->getDependencyContainer()->createShipmentOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

    public function saveShipmentForOrder(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponse){
        $this->getDependencyContainer()->createShipmentOrderSaver()->saveShipmentForOrder($orderTransfer, $checkoutResponse);
    }

}
