<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ShipmentFormTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface ShipmentGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransfer(ShipmentFormTransfer $shipmentFormTransfer): ShipmentGroupTransfer;
}
