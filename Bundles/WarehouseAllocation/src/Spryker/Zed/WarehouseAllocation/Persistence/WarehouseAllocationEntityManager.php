<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation\Persistence;

use Generated\Shared\Transfer\WarehouseAllocationTransfer;
use Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\WarehouseAllocation\Persistence\WarehouseAllocationPersistenceFactory getFactory()
 */
class WarehouseAllocationEntityManager extends AbstractEntityManager implements WarehouseAllocationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseAllocationTransfer $warehouseAllocationTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationTransfer
     */
    public function createWarehouseAllocation(
        WarehouseAllocationTransfer $warehouseAllocationTransfer
    ): WarehouseAllocationTransfer {
        $warehouseAllocationEntity = $this->getFactory()
            ->createWarehouseAllocationMapper()
            ->mapWarehouseAllocationTransferToWarehouseAllocationEntity($warehouseAllocationTransfer, new SpyWarehouseAllocation());

        $warehouseAllocationEntity->save();

        return $this->getFactory()
            ->createWarehouseAllocationMapper()
            ->mapWarehouseAllocationEntityToWarehouseAllocationTransfer($warehouseAllocationEntity, new WarehouseAllocationTransfer());
    }
}
