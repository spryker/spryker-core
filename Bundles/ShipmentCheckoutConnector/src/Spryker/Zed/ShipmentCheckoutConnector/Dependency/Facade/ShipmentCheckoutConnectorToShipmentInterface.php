<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

interface ShipmentCheckoutConnectorToShipmentInterface
{

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer
     *
     * @return ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransfer);

}
