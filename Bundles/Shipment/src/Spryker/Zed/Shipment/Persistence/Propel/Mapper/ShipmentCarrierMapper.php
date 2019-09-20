<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;

class ShipmentCarrierMapper
{
    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentCarrier $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function mapShipmentCarrierEntityToShipmentCarrierTransfer(
        SpyShipmentCarrier $salesShipmentEntity,
        ShipmentCarrierTransfer $shipmentCarrierTransfer
    ): ShipmentCarrierTransfer {
        return $shipmentCarrierTransfer->fromArray($salesShipmentEntity->toArray());
    }
}
