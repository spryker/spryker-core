<?php


namespace Spryker\Zed\Shipment\Business\Model;


use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class ShipmentCarrierReader
{
    /**
     * @var ShipmentQueryContainerInterface
     */
    protected $shipmentQueryContainer;

    /**
     * @param ShipmentQueryContainerInterface $shipmentQueryContainer
     */
    public function __construct(ShipmentQueryContainerInterface $shipmentQueryContainer)
    {
        $this->shipmentQueryContainer = $shipmentQueryContainer;
    }

    /**
     * @return ShipmentCarrierTransfer[]
     */
    public function findCarriers()
    {
        $query = $this->shipmentQueryContainer
            ->queryCarriers();

        $shipmentCarrierTransfers = [];

        foreach ($query->find() as $spyShipmentCarrier) {
            $shipmentCarrierTransfer = new ShipmentCarrierTransfer();
            $shipmentCarrierTransfer = $this->mapEntityToTransfer($spyShipmentCarrier, $shipmentCarrierTransfer);
            $shipmentCarrierTransfers[] = $shipmentCarrierTransfer;
        }

        return $shipmentCarrierTransfers;
    }

    /**
     * @param SpyShipmentCarrier $spyShipmentCarrier
     * @param ShipmentCarrierTransfer $shipmentCarrierTransfer
     *
     * @return ShipmentCarrierTransfer
     */
    protected function mapEntityToTransfer(SpyShipmentCarrier $spyShipmentCarrier, ShipmentCarrierTransfer $shipmentCarrierTransfer)
    {
        $shipmentCarrierTransfer->fromArray($spyShipmentCarrier->toArray(), true);

        return $shipmentCarrierTransfer;
    }

}