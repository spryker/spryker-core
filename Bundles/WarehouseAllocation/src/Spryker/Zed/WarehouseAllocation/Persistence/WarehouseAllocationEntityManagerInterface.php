<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Persistence;

use Generated\Shared\Transfer\WarehouseAllocationTransfer;

interface WarehouseAllocationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseAllocationTransfer $warehouseAllocationTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationTransfer
     */
    public function createWarehouseAllocation(
        WarehouseAllocationTransfer $warehouseAllocationTransfer
    ): WarehouseAllocationTransfer;
}
