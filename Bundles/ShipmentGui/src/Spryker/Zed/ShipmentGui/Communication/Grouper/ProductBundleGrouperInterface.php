<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;

interface ProductBundleGrouperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroupTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<array<\Generated\Shared\Transfer\ProductBundleGroupTransfer>>
     */
    public function groupBundleItemsByShipmentGroupHash(ArrayObject $shipmentGroupTransfers, OrderTransfer $orderTransfer): array;
}
