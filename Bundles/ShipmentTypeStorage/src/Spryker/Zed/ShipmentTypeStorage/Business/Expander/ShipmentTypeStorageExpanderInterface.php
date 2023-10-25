<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business\Expander;

interface ShipmentTypeStorageExpanderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     * @param string $storeName
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expandShipmentTypeStorageTransfers(array $shipmentTypeStorageTransfers, string $storeName): array;
}
