<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

class ShipmentCheckoutConnectorToShipmentBridge implements ShipmentCheckoutConnectorToShipmentInterface
{

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentFacade
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentFacade $shipmentFacade
     */
    public function __construct($shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransferTransfer
     *
     * @return ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransferTransfer)
    {
        return $this->shipmentFacade->getAvailableMethods($shipmentMethodAvailabilityTransferTransfer);
    }

}
