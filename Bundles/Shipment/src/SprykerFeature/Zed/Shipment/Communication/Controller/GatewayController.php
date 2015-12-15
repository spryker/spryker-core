<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Communication\Controller;

use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Spryker\Zed\Shipment\Business\ShipmentFacade;

/**
 * @method ShipmentFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability
     *
     * @return ShipmentTransfer
     */
    public function getAvailableMethodsAction(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailability)
    {
        return $this->getFacade()
            ->getAvailableMethods($shipmentMethodAvailability);
    }

}
