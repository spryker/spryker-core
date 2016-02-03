<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer;

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
     * @param \Generated\Shared\Transfer\ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransferTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getAvailableMethods(ShipmentMethodAvailabilityTransfer $shipmentMethodAvailabilityTransferTransfer)
    {
        return $this->shipmentFacade->getAvailableMethods($shipmentMethodAvailabilityTransferTransfer);
    }

}
