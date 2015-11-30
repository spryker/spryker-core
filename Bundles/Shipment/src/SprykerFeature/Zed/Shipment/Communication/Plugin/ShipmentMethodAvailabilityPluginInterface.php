<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;

interface ShipmentMethodAvailabilityPluginInterface
{

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     *
     * @return bool
     */
    public function isAvailable(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability);

}
