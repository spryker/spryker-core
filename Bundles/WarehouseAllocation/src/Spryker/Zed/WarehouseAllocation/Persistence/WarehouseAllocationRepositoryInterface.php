<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Persistence;

use Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer;
use Generated\Shared\Transfer\WarehouseAllocationCriteriaTransfer;

interface WarehouseAllocationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseAllocationCriteriaTransfer $warehouseAllocationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationCollectionTransfer
     */
    public function getWarehouseAllocationCollection(
        WarehouseAllocationCriteriaTransfer $warehouseAllocationCriteriaTransfer
    ): WarehouseAllocationCollectionTransfer;
}
