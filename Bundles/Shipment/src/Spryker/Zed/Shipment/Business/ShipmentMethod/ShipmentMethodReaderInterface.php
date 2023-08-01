<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface ShipmentMethodReaderInterface
{
    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodById(int $idShipmentMethod): ?ShipmentMethodTransfer;

    /**
     * @param string $shipmentMethodName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByName(string $shipmentMethodName): ?ShipmentMethodTransfer;

    /**
     * @param string $shipmentMethodKey
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByKey(string $shipmentMethodKey): ?ShipmentMethodTransfer;

    /**
     * @return list<\Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function getActiveShipmentMethods(): array;
}
