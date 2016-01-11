<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Shipment\Business\ShipmentFacade;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacade getFacade()
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
        return $this->getFacade()->getAvailableMethods($shipmentMethodAvailabilityTransfer);
    }

}
