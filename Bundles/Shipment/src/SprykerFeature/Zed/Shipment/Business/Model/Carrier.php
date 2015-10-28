<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;

class Carrier
{

    /**
     * @param ShipmentCarrierTransfer $carrierTransfer
     *
     * @throws PropelException
     *
     * @return int
     */
    public function create(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierEntity = new SpyShipmentCarrier();
        $carrierEntity
            ->setName($carrierTransfer->getName())
            ->setGlossaryKeyName(
                $carrierTransfer->getGlossaryKeyName()
            )
            ->setIsActive($carrierTransfer->getIsActive())
            ->save()
        ;

        return $carrierEntity->getPrimaryKey();
    }

}
