<?php

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;


use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface;

class ShipmentDiscountReader implements ShipmentDiscountReaderInterface
{

    /**
     * @var ShipmentDiscountConnectorToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @param ShipmentDiscountConnectorToShipmentInterface $shipmentFacade
     */
    public function __construct(ShipmentDiscountConnectorToShipmentInterface $shipmentFacade) {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @return array
     */
    public function getCarrierList()
    {
        $shipmentCarrierTransfers = $this->shipmentFacade->findCarriers();

        $list = [];
        foreach ($shipmentCarrierTransfers as $shipmentCarrierTransfer) {
            $list[$shipmentCarrierTransfer->getIdShipmentCarrier()] = $shipmentCarrierTransfer->getName();
        }

        return $list;
    }

}