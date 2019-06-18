<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\ShipmentHashing;

use Generated\Shared\Transfer\ShipmentTransfer;

interface ShipmentHashingInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    public function getShipmentHashKey(ShipmentTransfer $shipmentTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param string $shipmentHashKey
     *
     * @return bool
     */
    public function isShipmentEqualShipmentHashKey(ShipmentTransfer $shipmentTransfer, string $shipmentHashKey): bool;
}
