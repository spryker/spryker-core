<?php

namespace Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade;


interface ShipmentDiscountConnectorToShipmentInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer[]
     */
    public function findCarriers();

}