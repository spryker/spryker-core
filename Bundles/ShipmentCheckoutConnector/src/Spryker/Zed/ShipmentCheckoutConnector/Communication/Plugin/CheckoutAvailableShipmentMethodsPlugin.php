<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentCheckoutConnector\Communication\ShipmentCheckoutConnectorCommunicationFactory;
use Spryker\Zed\ShipmentCheckoutConnector\Business\ShipmentCheckoutConnectorFacade;

/**
 * @method ShipmentCheckoutConnectorCommunicationFactory getFactory()
 * @method ShipmentCheckoutConnectorFacade getFacade()
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
        return $this->getFactory()->getShipmentFacade()->getAvailableMethods($shipmentMethodAvailabilityTransfer);
    }

}
