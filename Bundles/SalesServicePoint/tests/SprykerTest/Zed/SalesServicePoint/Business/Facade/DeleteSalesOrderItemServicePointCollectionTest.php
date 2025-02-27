<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderItemServicePointCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use SprykerTest\Zed\SalesServicePoint\SalesServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesServicePoint
 * @group Business
 * @group Facade
 * @group DeleteSalesOrderItemServicePointCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderItemServicePointCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesServicePoint\SalesServicePointBusinessTester
     */
    protected SalesServicePointBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemServicePointDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeletesSalesOrderItemServicePointEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->haveSalesOrderItemServicePoint([
            SalesOrderItemServicePointTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItemToDelete,
        ]);
        $salesOrderItemServicePointTransfer = $this->tester->haveSalesOrderItemServicePoint([
            SalesOrderItemServicePointTransfer::ID_SALES_ORDER_ITEM => $salesOrderItemEntity->getIdSalesOrderItem(),
        ]);
        $salesOrderItemServicePointCollectionDeleteCriteriaTransfer = (new SalesOrderItemServicePointCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItemToDelete);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemServicePointCollection($salesOrderItemServicePointCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemServicePointEntities = $this->tester->getSalesOrderItemServicePointEntities();

        $this->assertCount(1, $salesOrderItemServicePointEntities);
        $this->assertSame($salesOrderItemServicePointTransfer->getIdSalesOrderItemServicePoint(), $salesOrderItemServicePointEntities[0]->getIdSalesOrderItemServicePoint());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemServicePointEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemServicePointTransfer = $this->tester->haveSalesOrderItemServicePoint([
            SalesOrderItemServicePointTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem,
        ]);
        $salesOrderItemServicePointCollectionDeleteCriteriaTransfer = (new SalesOrderItemServicePointCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemServicePointCollection($salesOrderItemServicePointCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemServicePointEntities = $this->tester->getSalesOrderItemServicePointEntities();

        $this->assertCount(1, $salesOrderItemServicePointEntities);
        $this->assertSame($salesOrderItemServicePointTransfer->getIdSalesOrderItemServicePoint(), $salesOrderItemServicePointEntities[0]->getIdSalesOrderItemServicePoint());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemServicePointEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemServicePointTransfer = $this->tester->haveSalesOrderItemServicePoint([
            SalesOrderItemServicePointTransfer::ID_SALES_ORDER_ITEM => $idSalesOrderItem,
        ]);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemServicePointCollection(new SalesOrderItemServicePointCollectionDeleteCriteriaTransfer());

        // Assert
        $salesOrderItemServicePointEntities = $this->tester->getSalesOrderItemServicePointEntities();

        $this->assertCount(1, $salesOrderItemServicePointEntities);
        $this->assertSame($salesOrderItemServicePointTransfer->getIdSalesOrderItemServicePoint(), $salesOrderItemServicePointEntities[0]->getIdSalesOrderItemServicePoint());
    }
}
