<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\ProductOptionFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderItemOptionCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\ProductOption\ProductOptionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group ProductOptionFacade
 * @group DeleteSalesOrderItemOptionCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderItemOptionCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionBusinessTester
     */
    protected ProductOptionBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemOptionTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeletesSalesOrderItemOptionEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->createSalesOrderItemOption($idSalesOrderItemToDelete);
        $salesOrderItemOptionEntity = $this->tester->createSalesOrderItemOption($salesOrderItemEntity->getIdSalesOrderItem());
        $salesOrderItemOptionCollectionDeleteCriteriaTransfer = (new SalesOrderItemOptionCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItemToDelete);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemOptionCollection($salesOrderItemOptionCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemOptionEntities = $this->tester->getSalesOrderItemOptionEntities();

        $this->assertCount(1, $salesOrderItemOptionEntities);
        $this->assertSame($salesOrderItemOptionEntity->getIdSalesOrderItemOption(), $salesOrderItemOptionEntities[0]->getIdSalesOrderItemOption());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemOptionEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemOptionEntity = $this->tester->createSalesOrderItemOption($idSalesOrderItem);
        $salesOrderItemOptionCollectionDeleteCriteriaTransfer = (new SalesOrderItemOptionCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemOptionCollection($salesOrderItemOptionCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemOptionEntities = $this->tester->getSalesOrderItemOptionEntities();

        $this->assertCount(1, $salesOrderItemOptionEntities);
        $this->assertSame($salesOrderItemOptionEntity->getIdSalesOrderItemOption(), $salesOrderItemOptionEntities[0]->getIdSalesOrderItemOption());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemOptionEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemOptionEntity = $this->tester->createSalesOrderItemOption($idSalesOrderItem);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemOptionCollection(new SalesOrderItemOptionCollectionDeleteCriteriaTransfer());

        // Assert
        $salesOrderItemOptionEntities = $this->tester->getSalesOrderItemOptionEntities();

        $this->assertCount(1, $salesOrderItemOptionEntities);
        $this->assertSame($salesOrderItemOptionEntity->getIdSalesOrderItemOption(), $salesOrderItemOptionEntities[0]->getIdSalesOrderItemOption());
    }
}
