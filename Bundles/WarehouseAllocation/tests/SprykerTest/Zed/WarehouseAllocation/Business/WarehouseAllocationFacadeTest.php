<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseAllocation\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehouseAllocationTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Zed\WarehouseAllocation\WarehouseAllocationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group WarehouseAllocation
 * @group Business
 * @group Facade
 * @group WarehouseAllocationFacadeTest
 * Add your own group annotations below this line
 */
class WarehouseAllocationFacadeTest extends Unit
{
    use DataCleanupHelperTrait;

    /**
     * @var \SprykerTest\Zed\WarehouseAllocation\WarehouseAllocationBusinessTester
     */
    protected WarehouseAllocationBusinessTester $tester;

    /**
     * @var string
     */
    protected const TEST_ORDER_ITEM_UUID = 'TEST_ORDER_ITEM_UUID';

    /**
     * @return void
     */
    public function testAllocateWarehousesWillNotPersistWarehouseAllocationIfOrderItemsDoNotHaveWarehouseSpecified(): void
    {
        // Arrange
        $orderTransfer = (new OrderBuilder())
            ->withItem()
            ->withAnotherItem()
            ->build();

        // Act
        $this->tester->getFacade()->allocateWarehouses($orderTransfer);

        // Assert
        $this->assertCount(0, $this->tester->getWarehouseAllocations());
    }

    /**
     * @return void
     */
    public function testAllocateWarehousesWillPersistWarehouseAllocationOnlyForOrderItemsWithSpecifiedWarehouse(): void
    {
        // Arrange
        $stockTransfer = $this->tester->haveStock();
        $orderTransfer = (new OrderBuilder())
            ->withItem([
                ItemTransfer::UUID => static::TEST_ORDER_ITEM_UUID,
                ItemTransfer::WAREHOUSE => [
                    StockTransfer::ID_STOCK => $stockTransfer->getIdStockOrFail(),
                ],
            ])
            ->withAnotherItem()
            ->build();

        // Act
        $this->tester->getFacade()->allocateWarehouses($orderTransfer);

        // Assert
        $warehouseAllocationEntities = $this->tester->getWarehouseAllocations();
        $this->assertCount(1, $warehouseAllocationEntities);

        /** @var \Orm\Zed\WarehouseAllocation\Persistence\SpyWarehouseAllocation $warehouseAllocationEntity */
        $warehouseAllocationEntity = $warehouseAllocationEntities->getIterator()->current();
        $this->assertSame($stockTransfer->getIdStockOrFail(), $warehouseAllocationEntity->getFkWarehouse());
        $this->assertSame(static::TEST_ORDER_ITEM_UUID, $warehouseAllocationEntity->getSalesOrderItemUuid());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithWarehousesReturnsExpandedOrderItems(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([ItemTransfer::UUID => static::TEST_ORDER_ITEM_UUID]))->build();
        $stockTransfer = $this->tester->haveStock();
        $this->tester->haveWarehouseAllocation([
            WarehouseAllocationTransfer::SALES_ORDER_ITEM_UUID => $itemTransfer->getUuid(),
            WarehouseAllocationTransfer::WAREHOUSE => [
                StockTransfer::ID_STOCK => $stockTransfer->getIdStock(),
            ],
        ]);

        // Act
        $itemTransfers = $this->tester->getFacade()->expandOrderItemsWithWarehouse([$itemTransfer]);

        // Assert
        $this->assertSame(static::TEST_ORDER_ITEM_UUID, $itemTransfers[0]->getUuid());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithWarehousesReturnsTheSameItemTransfersIfThereIsNoWarehouseAllocationFound(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([ItemTransfer::UUID => static::TEST_ORDER_ITEM_UUID]))->build();

        // Act
        $itemTransfers = $this->tester->getFacade()->expandOrderItemsWithWarehouse([$itemTransfer]);

        // Assert
        $this->assertNull($itemTransfers[0]->getWarehouse());
        $this->assertSame($itemTransfer->getUuid(), $itemTransfers[0]->getUuid());
        $this->assertSame($itemTransfer->getId(), $itemTransfers[0]->getId());
    }
}
