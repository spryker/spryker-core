<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Shipment;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method ShipmentFactory getFactory()
 */
class ShipmentClient extends AbstractClient implements ShipmentClientInterface
{

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability)
    {
        return $this->getFactory()
            ->createZedStub()
            ->getAvailableMethods($shipmentMethodAvailability);
    }

}
