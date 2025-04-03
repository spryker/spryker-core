<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\ShipmentTypeListStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;

interface ShipmentTypeStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeListStorageTransfer $shipmentTypeListStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveShipmentTypeListStorage(ShipmentTypeListStorageTransfer $shipmentTypeListStorageTransfer, string $storeName): void;

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveShipmentTypeStorage(ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer, string $storeName): void;

    /**
     * @param list<int> $shipmentTypeIds
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteShipmentTypeStorageByShipmentTypeIds(array $shipmentTypeIds, ?string $storeName = null): void;

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @return bool
     */
    public function isShipmentTypeListStorageTableExists(): bool;
}
