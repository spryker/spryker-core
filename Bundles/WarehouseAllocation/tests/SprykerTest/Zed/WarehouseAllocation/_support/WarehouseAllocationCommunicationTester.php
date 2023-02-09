<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseAllocation;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation;
use Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery;
use Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class WarehouseAllocationCommunicationTester extends Actor
{
    use _generated\WarehouseAllocationCommunicationTesterActions;

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function haveOrderWithOneItem(): SpySalesOrder
    {
        return $this->haveSalesOrderEntity([
            (new ItemBuilder([ItemTransfer::NAME => 'test']))->build(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer|null $stockTransfer
     *
     * @return \Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface
     */
    public function createSalesOrderWarehouseAllocationPluginMock(
        ?StockTransfer $stockTransfer = null
    ): SalesOrderWarehouseAllocationPluginInterface {
        return Stub::makeEmpty(
            SalesOrderWarehouseAllocationPluginInterface::class,
            [
                'allocateWarehouse' =>
                    function (OrderTransfer $orderTransfer) use ($stockTransfer) {
                        $orderTransfer->getItems()->offsetGet(0)->setWarehouse($stockTransfer);

                        return $orderTransfer;
                    },
            ],
        );
    }

    /**
     * @param string $salesOrderItemUuid
     * @param int|null $idWarehouse
     *
     * @return \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation|null
     */
    public function findWarehouseAssignment(string $salesOrderItemUuid, ?int $idWarehouse = null): ?SpyWarehouseAllocation
    {
        $spyWarehouseAllocationQuery = $this->getWarehouseAllocationQuery()
            ->filterBySalesOrderItemUuid($salesOrderItemUuid);

        if ($idWarehouse) {
            $spyWarehouseAllocationQuery->filterByFkWarehouse($idWarehouse);
        }

        return $spyWarehouseAllocationQuery->findOne();
    }

    /**
     * @return \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocationQuery<\Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation>
     */
    protected function getWarehouseAllocationQuery(): SpyWarehouseAllocationQuery
    {
        return SpyWarehouseAllocationQuery::create();
    }
}
