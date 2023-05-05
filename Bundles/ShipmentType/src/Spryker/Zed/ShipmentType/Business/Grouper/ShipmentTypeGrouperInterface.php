<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer;

interface ShipmentTypeGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer $shipmentTypeCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer>>
     */
    public function groupShipmentTypeTransfersByValidity(ShipmentTypeCollectionResponseTransfer $shipmentTypeCollectionResponseTransfer): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $baseShipmentTypeTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $additionalShipmentTypeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function mergeShipmentTypeTransfers(ArrayObject $baseShipmentTypeTransfers, ArrayObject $additionalShipmentTypeTransfers): ArrayObject;
}
