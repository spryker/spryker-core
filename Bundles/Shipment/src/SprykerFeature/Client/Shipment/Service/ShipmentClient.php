<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Shipment\Service;

use Generated\Shared\Shipment\ShipmentInterface;
use Generated\Shared\Shipment\ShipmentMethodAvailabilityInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method ShipmentDependencyContainer getDependencyContainer()
 */
class ShipmentClient extends AbstractClient implements ShipmentClientInterface
{

    /**
     * @param ShipmentMethodAvailabilityInterface $shipmentMethodAvailability
     *
     * @return ShipmentInterface
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityInterface $shipmentMethodAvailability)
    {
        return $this->getDependencyContainer()
            ->createZedStub()
            ->getAvailableMethods($shipmentMethodAvailability)
            ;
    }
}
