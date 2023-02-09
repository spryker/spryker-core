<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseAllocation\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\WarehouseAllocationBuilder;
use Generated\Shared\Transfer\WarehouseAllocationTransfer;
use Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class WarehouseUserAllocationHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\WarehouseAllocationTransfer
     */
    public function haveWarehouseAllocation(array $seedData = []): WarehouseAllocationTransfer
    {
        $warehouseAllocationTransfer = (new WarehouseAllocationBuilder($seedData))->build();

        $warehouseAllocationEntity = (new SpyWarehouseAllocation())
            ->setSalesOrderItemUuid($warehouseAllocationTransfer->getSalesOrderItemUuidOrFail())
            ->setFkWarehouse($warehouseAllocationTransfer->getWarehouseOrFail()->getIdStockOrFail());
        $warehouseAllocationEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($warehouseAllocationEntity): void {
            $warehouseAllocationEntity->delete();
        });

        return $warehouseAllocationTransfer->fromArray($warehouseAllocationEntity->toArray(), true);
    }
}
