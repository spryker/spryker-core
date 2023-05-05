<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Persistence;

use Generated\Shared\Transfer\ShipmentTypeTransfer;

interface ShipmentTypeEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function createShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function updateShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeTransfer;

    /**
     * @param int $idShipmentType
     * @param list<int> $idStores
     *
     * @return void
     */
    public function createShipmentTypeStoreRelations(int $idShipmentType, array $idStores): void;

    /**
     * @param int $idShipmentType
     * @param list<int> $idStores
     *
     * @return void
     */
    public function deleteShipmentTypeStoreRelations(int $idShipmentType, array $idStores): void;
}
