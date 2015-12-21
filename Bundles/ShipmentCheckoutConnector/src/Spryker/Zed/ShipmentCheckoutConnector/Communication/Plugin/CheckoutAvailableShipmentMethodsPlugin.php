<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentCheckoutConnector\Communication\ShipmentCheckoutConnectorCommunicationFactory;

/**
 * @method ShipmentCheckoutConnectorCommunicationFactory getFactory()
 */
class CheckoutAvailableShipmentMethodsPlugin extends AbstractPlugin
{

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer)
    {
        return $this->getFactory()->createShipmentFacade()->getAvailableMethods($shipmentMethodAvailabilityTransfer);
    }

}
