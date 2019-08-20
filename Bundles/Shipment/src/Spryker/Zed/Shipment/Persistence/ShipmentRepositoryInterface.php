<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface ShipmentRepositoryInterface
{
    /**
     * - Requires ShipmentMethodTransfer::name field to be set in ShipmentMethodTransfer
     * - Requires ShipmentMethodTransfer::fkShipmentCarrier field to be set in ShipmentMethodTransfer
     *
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function isShipmentMethodUniqueForCarrier(ShipmentMethodTransfer $shipmentMethodTransfer): bool;
}
