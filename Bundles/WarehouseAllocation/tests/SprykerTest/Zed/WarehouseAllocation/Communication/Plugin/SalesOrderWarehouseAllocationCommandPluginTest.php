<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WarehouseAllocation\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\WarehouseAllocation\Communication\Plugin\Oms\SalesOrderWarehouseAllocationCommandPlugin;
use Spryker\Zed\WarehouseAllocation\WarehouseAllocationDependencyProvider;
use SprykerTest\Zed\WarehouseAllocation\WarehouseAllocationCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group WarehouseAllocation
 * @group Communication
 * @group Plugin
 * @group SalesOrderWarehouseAllocationCommandPluginTest
 * Add your own group annotations below this line
 */
class SalesOrderWarehouseAllocationCommandPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\WarehouseAllocation\WarehouseAllocationCommunicationTester
     */
    protected WarehouseAllocationCommunicationTester $tester;

    /**
     * @return void
     */
    public function testRunAssignsWarehouseIdWhenSalesOrderWarehouseAllocationPluginSetsWarehouseIdToItemWarehouseTransfer(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->haveOrderWithOneItem();
        $salesOrderItemEntity = $salesOrderEntity->getItems()->offsetGet(0);
        $stockTransfer = $this->tester->haveStock();
        $this->tester->setDependency(
            WarehouseAllocationDependencyProvider::PLUGINS_SALES_ORDER_WAREHOUSE_ALLOCATION,
            [$this->tester->createSalesOrderWarehouseAllocationPluginMock($stockTransfer)],
        );
        $salesOrderWarehouseAllocationCommandPlugin = new SalesOrderWarehouseAllocationCommandPlugin();

        // Act
        $salesOrderWarehouseAllocationCommandPlugin->run([$salesOrderItemEntity], $salesOrderEntity, new ReadOnlyArrayObject());

        // Assert
        $warehouseAllocationEntity = $this->tester->findWarehouseAssignment(
            $salesOrderItemEntity->getUuid(),
            $stockTransfer->getIdStock(),
        );
        $this->assertNotNull($warehouseAllocationEntity);
        $this->assertSame($stockTransfer->getIdStock(), $warehouseAllocationEntity->getFkWarehouse());
        $this->assertSame($salesOrderItemEntity->getUuid(), $warehouseAllocationEntity->getSalesOrderItemUuid());
    }

    /**
     * @dataProvider warehouseDataProvider
     *
     * @param \Generated\Shared\Transfer\StockTransfer|null $stockTransfer
     *
     * @return void
     */
    public function testRunThrowsExceptionWhenSalesOrderWarehouseAllocationPluginDoesNotSetWarehouseToItemTransfer(
        ?StockTransfer $stockTransfer = null
    ): void {
        // Arrange
        $salesOrderEntity = $this->tester->haveOrderWithOneItem();
        $salesOrderItemEntity = $salesOrderEntity->getItems()->offsetGet(0);
        $salesOrderWarehouseAllocationCommandPlugin = new SalesOrderWarehouseAllocationCommandPlugin();
        $this->tester->setDependency(
            WarehouseAllocationDependencyProvider::PLUGINS_SALES_ORDER_WAREHOUSE_ALLOCATION,
            [$this->tester->createSalesOrderWarehouseAllocationPluginMock($stockTransfer)],
        );

        // Act
        $salesOrderWarehouseAllocationCommandPlugin->run([$salesOrderItemEntity], $salesOrderEntity, new ReadOnlyArrayObject());

        // Assert
        $warehouseAllocationEntity = $this->tester->findWarehouseAssignment($salesOrderItemEntity->getUuid());

        $this->assertNull($warehouseAllocationEntity);
    }

    /**
     * @return list<\Generated\Shared\Transfer\StockTransfer|null>
     */
    public function warehouseDataProvider(): array
    {
        return [[new StockTransfer()], [null]];
    }
}
