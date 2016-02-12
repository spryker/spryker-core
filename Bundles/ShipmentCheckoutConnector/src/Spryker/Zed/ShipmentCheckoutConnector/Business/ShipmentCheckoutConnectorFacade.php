<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
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
