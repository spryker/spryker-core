<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTransfer;

interface ShipmentServiceInterface
{
    /**
     * Specification:
     * - Iterates all items grouping them by shipment.
     *
     * @api
     *
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfersCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function groupItemsByShipment(iterable $itemTransfersCollection): ArrayObject;

    /**
     * Specification:
     * - Returns hash based on shipping address, shipment method and requested delivery date.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return string
     */
    public function getShipmentHashKey(ShipmentTransfer $shipmentTransfer): string;

    /**
     * Specification:
     * - Returns true if shipping address equal with shipment hash key or false if not.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param string $shipmentHashKey
     *
     * @return bool
     */
    public function isShipmentEqualShipmentHashKey(ShipmentTransfer $shipmentTransfer, string $shipmentHashKey): bool;
}
