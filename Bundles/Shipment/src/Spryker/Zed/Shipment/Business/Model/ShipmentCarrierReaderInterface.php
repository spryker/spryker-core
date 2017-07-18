<?php


namespace Spryker\Zed\Shipment\Business\Model;


use Generated\Shared\Transfer\ShipmentCarrierTransfer;

interface ShipmentCarrierReaderInterface
{
    /**
     * @return ShipmentCarrierTransfer[]
     */
    public function findCarriers();

}