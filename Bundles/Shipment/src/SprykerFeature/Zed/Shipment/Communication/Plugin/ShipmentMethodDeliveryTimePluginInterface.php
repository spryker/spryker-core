<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;

interface ShipmentMethodDeliveryTimePluginInterface
{

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     *
     * @return int
     */
    public function getTime(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability);

}
