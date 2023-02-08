<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseUser\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\WarehouseUserAssignmentBuilder;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;
use Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class WarehouseUserHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function haveWarehouseUserAssignment(UserTransfer $userTransfer, StockTransfer $stockTransfer, array $seedData = []): WarehouseUserAssignmentTransfer
    {
        $warehouseUserAssignmentTransfer = (new WarehouseUserAssignmentBuilder($seedData))->build();
        $warehouseUserAssignmentTransfer->setWarehouse($stockTransfer);
        $warehouseUserAssignmentTransfer->setUserUuid($userTransfer->getUuidOrFail());

        $warehouseUserAssignmentEntity = $this->getWarehouseUserAssignmentQuery()
            ->filterByFkWarehouse($warehouseUserAssignmentTransfer->getWarehouseOrFail()->getIdStockOrFail())
            ->filterByUserUuid($warehouseUserAssignmentTransfer->getUserUuidOrFail())
            ->findOneOrCreate();

        $warehouseUserAssignmentEntity->fromArray($warehouseUserAssignmentTransfer->toArray());
        $warehouseUserAssignmentEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($warehouseUserAssignmentEntity): void {
            $warehouseUserAssignmentEntity->delete();
        });

        return $warehouseUserAssignmentTransfer->fromArray($warehouseUserAssignmentEntity->toArray(), true);
    }

    /**
     * @return void
     */
    public function ensureWarehouseUserAssignmentTableIsEmpty(): void
    {
        $this->getWarehouseUserAssignmentQuery()->deleteAll();
    }

    /**
     * @return \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery
     */
    protected function getWarehouseUserAssignmentQuery(): SpyWarehouseUserAssignmentQuery
    {
        return SpyWarehouseUserAssignmentQuery::create();
    }
}
