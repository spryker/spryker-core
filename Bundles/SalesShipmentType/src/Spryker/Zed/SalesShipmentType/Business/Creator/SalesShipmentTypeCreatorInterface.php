<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Creator;

use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

interface SalesShipmentTypeCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentTypeTransfer
     */
    public function createSalesShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer): SalesShipmentTypeTransfer;
}
