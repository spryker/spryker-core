<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Shipment\Service;

use Generated\Shared\Shipment\ShipmentInterface;
use Generated\Shared\Shipment\ShipmentMethodAvailabilityInterface;

interface ShipmentClientInterface
{

    /**
     * @param ShipmentMethodAvailabilityInterface $shipmentMethodAvailability
     *
     * @return ShipmentInterface
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityInterface $shipmentMethodAvailability);

}
