<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Communication\Controller;

use Generated\Shared\Shipment\ShipmentInterface;
use Generated\Shared\Shipment\ShipmentMethodAvailabilityInterface;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\Shipment\Business\ShipmentFacade;

/**
 * @method ShipmentFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param ShipmentMethodAvailabilityInterface $shipmentMethodAvailability
     *
     * @return ShipmentInterface
     */
    public function getAvailableMethodsAction(ShipmentMethodAvailabilityInterface $shipmentMethodAvailability)
    {
        return $this->getFacade()
            ->getAvailableMethods($shipmentMethodAvailability)
            ;
    }

}
