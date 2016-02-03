<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ShipmentCheckoutConnector\Communication\ShipmentCheckoutConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentCheckoutConnector\Business\ShipmentCheckoutConnectorFacade getFacade()
 */
class CheckoutAvailableShipmentMethodsPlugin extends AbstractPlugin
{

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer)
    {
        return $this->getFactory()->getShipmentFacade()->getAvailableMethods($shipmentMethodAvailabilityTransfer);
    }

}
