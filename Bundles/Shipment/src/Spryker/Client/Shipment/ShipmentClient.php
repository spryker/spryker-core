<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Shipment;

use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method ShipmentDependencyContainer getDependencyContainer()
 */
class ShipmentClient extends AbstractClient implements ShipmentClientInterface
{

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     *
     * @return ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability)
    {
        return $this->getDependencyContainer()
            ->createZedStub()
            ->getAvailableMethods($shipmentMethodAvailability);
    }

}
