<?php

namespace Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade;


use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\ShipmentFacadeInterface;

class ShipmentDiscountConnectorToShipmentBridge implements ShipmentDiscountConnectorToShipmentInterface
{

    /**
     * @var ShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param ShipmentFacadeInterface $shipmentFacade
     */
    public function __construct($shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer[]
     */
    public function findCarriers()
    {
        return $this->shipmentFacade->findCarriers();
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return ShipmentMethodTransfer|null
     */
    public function findMethodById($idShipmentMethod)
    {
        return $this->shipmentFacade->findMethodById($idShipmentMethod);
    }

}