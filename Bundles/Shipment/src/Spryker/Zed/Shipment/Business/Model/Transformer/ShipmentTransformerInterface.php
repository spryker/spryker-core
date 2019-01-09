<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model\Transformer;

use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;

interface ShipmentTransformerInterface
{
    /**
     * @param \Orm\Zed\Shipment\Persistence\SpySalesShipment $shipmentEntity
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function transformEntityToTransfer(SpySalesShipment $shipmentEntity): ShipmentTransfer;
}
