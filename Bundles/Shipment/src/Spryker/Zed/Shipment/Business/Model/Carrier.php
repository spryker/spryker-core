<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;

class Carrier
{

    /**
     * @param ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     */
    public function create(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierEntity = new SpyShipmentCarrier();
        $carrierEntity
            ->setName($carrierTransfer->getName())
            ->setIsActive($carrierTransfer->getIsActive())
            ->save();

        return $carrierEntity->getPrimaryKey();
    }

}
