<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Mapper;

use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

class SalesShipmentTypeMapper implements SalesShipmentTypeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Generated\Shared\Transfer\SalesShipmentTypeTransfer $salesShipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentTypeTransfer
     */
    public function mapShipmentTypeTransferToSalesShipmentTypeTransfer(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        SalesShipmentTypeTransfer $salesShipmentTypeTransfer
    ): SalesShipmentTypeTransfer {
        return $salesShipmentTypeTransfer->fromArray($shipmentTypeTransfer->toArray(), true);
    }
}
