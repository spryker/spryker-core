<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Expander;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface ShipmentMethodExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function expandShipmentMethodTransfer(ShipmentMethodTransfer $shipmentMethodTransfer): ShipmentMethodTransfer;

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethodTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function expandShipmentMethodTransfers(array $shipmentMethodTransfers): array;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function expandShipmentMethodCollectionTransfer(
        ShipmentMethodCollectionTransfer $shipmentMethodCollectionTransfer
    ): ShipmentMethodCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    public function expandShipmentMethodsCollectionTransfer(
        ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer
    ): ShipmentMethodsCollectionTransfer;
}
