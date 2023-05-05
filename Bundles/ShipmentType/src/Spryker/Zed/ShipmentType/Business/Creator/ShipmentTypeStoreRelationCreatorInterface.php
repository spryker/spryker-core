<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Creator;

use Generated\Shared\Transfer\ShipmentTypeTransfer;

interface ShipmentTypeStoreRelationCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function createShipmentTypeStoreRelations(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeTransfer;
}
