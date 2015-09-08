<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Shipment\ShipmentMethodAvailabilityInterface;

interface ShipmentMethodDeliveryTimePluginInterface
{

    /**
     * @param ShipmentMethodAvailabilityInterface $shipmentMethodAvailability
     *
     * @return int
     */
    public function getTime(ShipmentMethodAvailabilityInterface $shipmentMethodAvailability);

}
