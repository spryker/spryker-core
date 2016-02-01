<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class ShipmentCheckoutConnectorToShipmentBridge implements ShipmentCheckoutConnectorToShipmentInterface
{

    /**
     * @var ShipmentFacade
     */
    protected $shipmentFacade;

    /**
     * @param ShipmentFacade $shipmentFacade
     */
    public function __construct($shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransferTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransferTransfer)
    {
        return $this->shipmentFacade->getAvailableMethods($shipmentMethodAvailabilityTransferTransfer);
    }

}
