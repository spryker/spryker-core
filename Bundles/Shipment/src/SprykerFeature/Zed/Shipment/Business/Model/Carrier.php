<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrier;

class Carrier
{
    /**
     * @param ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     * @throws PropelException
     */
    public function create(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierEntity = new SpyShipmentCarrier();
        $carrierEntity
            ->setFkGlossaryKeyCarrierName(
                $carrierTransfer->getFkGlossaryKeyCarrierName()
            );
        $carrierEntity->setIsActive($carrierTransfer->getIsActive());
        $carrierEntity->save();

        return $carrierEntity->getPrimaryKey();
    }
}
