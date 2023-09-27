<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Expander;

use ArrayObject;

interface WarehouseUserAssignmentExpanderInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>
     */
    public function expandWarehouseUserAssignmentTransfersWithWarehouses(ArrayObject $warehouseUserAssignmentTransfers): ArrayObject;
}
