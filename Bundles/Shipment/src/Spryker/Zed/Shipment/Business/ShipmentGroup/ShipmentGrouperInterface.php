<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentGroup;

use Generated\Shared\Transfer\ShipmentGroupTransfer;

interface ShipmentGrouperInterface
{
    /**
     * @param array $formData
     * @param int|null $idCustomerAddress
     * @param int|null $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function createShipmentGroupTransfer(array $formData, ?int $idCustomerAddress, ?int $idShipmentMethod): ShipmentGroupTransfer;
}
