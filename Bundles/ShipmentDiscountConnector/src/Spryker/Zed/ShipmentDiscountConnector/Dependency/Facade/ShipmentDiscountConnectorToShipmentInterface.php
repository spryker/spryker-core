<?php

namespace Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade;


use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface ShipmentDiscountConnectorToShipmentInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer[]
     */
    public function findCarriers();

    /**
     * @param int $idShipmentMethod
     *
     * @return ShipmentMethodTransfer|null
     */
    public function findMethodById($idShipmentMethod);

}