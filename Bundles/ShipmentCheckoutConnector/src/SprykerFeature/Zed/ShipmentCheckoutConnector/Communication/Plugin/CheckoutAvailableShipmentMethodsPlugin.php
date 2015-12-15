<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentCheckoutConnector\Communication\ShipmentCheckoutConnectorDependencyContainer;

/**
 * @method ShipmentCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CheckoutAvailableShipmentMethodsPlugin extends AbstractPlugin
{

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return \Generated\Shared\Shipment\ShipmentInterface
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer)
    {
        return $this->getDependencyContainer()->createShipmentFacade()->getAvailableMethods($shipmentMethodAvailabilityTransfer);
    }

}
