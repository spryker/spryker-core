<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentTransfer;

interface ShipmentGuiToShipmentInterface
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getMethods();

    /**
     * @api
     *
     * @param int $idShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idShipment): ?ShipmentTransfer;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return void
     */
    public function saveShipment(ShipmentTransfer $shipmentTransfer): void;
}
