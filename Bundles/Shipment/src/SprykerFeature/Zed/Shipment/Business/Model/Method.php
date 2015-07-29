<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentMethodTransfer;

use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentMethod;

class Method
{
    /**
     * @param ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function create(ShipmentMethodTransfer $methodTransfer)
    {
        $methodEntity = new SpyShipmentMethod();
        $methodEntity
            ->setFkShipmentCarrier($methodTransfer->getFkShipmentCarrier())
            ->setFkGlossaryKeyMethodName(
                $methodTransfer->getFkGlossaryKeyMethodName()
            )
            ->setFkGlossaryKeyMethodDescription(
                $methodTransfer->getFkGlossaryKeyMethodDescription()
            )
            ->setPrice($methodTransfer->getPrice())
            ->setName($methodTransfer->getName())
            ->setIsActive($methodTransfer->getIsActive())
            ->save()
        ;

        return $methodEntity->getPrimaryKey();
    }
}
