<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;

class Carrier implements CarrierInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $carrierTransfer
     *
     * @return int
     */
    public function create(ShipmentCarrierTransfer $carrierTransfer)
    {
        $carrierEntity = new SpyShipmentCarrier();
        $carrierEntity
            ->setName($carrierTransfer->getName())
            ->setGlossaryKeyName($carrierTransfer->getGlossaryKeyName())
            ->setIsActive($carrierTransfer->getIsActive())
            ->save();

        return $carrierEntity->getPrimaryKey();
    }
}
