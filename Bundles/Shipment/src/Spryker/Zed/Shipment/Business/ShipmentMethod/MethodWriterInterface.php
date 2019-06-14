<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface MethodWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return int|null
     */
    public function create(ShipmentMethodTransfer $shipmentMethodTransfer): ?int;

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function delete(int $idShipmentMethod): bool;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function update(ShipmentMethodTransfer $shipmentMethodTransfer): bool;
}
