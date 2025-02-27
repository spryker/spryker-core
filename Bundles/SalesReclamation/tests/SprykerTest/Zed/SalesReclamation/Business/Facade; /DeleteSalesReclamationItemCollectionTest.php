<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReclamation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesReclamationItemCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\SalesReclamation\SalesReclamationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReclamation
 * @group Business
 * @group Facade
 * @group DeleteSalesReclamationItemCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesReclamationItemCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesReclamation\SalesReclamationBusinessTester
     */
    protected SalesReclamationBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesReclamationItemTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeletesSalesReclamationItemEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $salesReclamationEntity = $this->tester->createSalesReclamation($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->createSalesReclamationItem($salesReclamationEntity->getIdSalesReclamation(), $idSalesOrderItemToDelete);
        $salesReclamationItemEntity = $this->tester->createSalesReclamationItem(
            $salesReclamationEntity->getIdSalesReclamation(),
            $salesOrderItemEntity->getIdSalesOrderItem(),
        );
        $salesReclamationItemCollectionDeleteCriteriaTransfer = (new SalesReclamationItemCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItemToDelete);

        // Act
        $this->tester->getFacade()->deleteSalesReclamationItemCollection($salesReclamationItemCollectionDeleteCriteriaTransfer);

        // Assert
        $salesReclamationItemEntities = $this->tester->getSalesReclamationItemEntities();

        $this->assertCount(1, $salesReclamationItemEntities);
        $this->assertSame($salesReclamationItemEntity->getIdSalesReclamationItem(), $salesReclamationItemEntities[0]->getIdSalesReclamationItem());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesReclamationItemEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesReclamationEntity = $this->tester->createSalesReclamation($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesReclamationItemEntity = $this->tester->createSalesReclamationItem(
            $salesReclamationEntity->getIdSalesReclamation(),
            $idSalesOrderItem,
        );
        $salesReclamationItemCollectionDeleteCriteriaTransfer = (new SalesReclamationItemCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteSalesReclamationItemCollection($salesReclamationItemCollectionDeleteCriteriaTransfer);

        // Assert
        $salesReclamationItemEntities = $this->tester->getSalesReclamationItemEntities();

        $this->assertCount(1, $salesReclamationItemEntities);
        $this->assertSame($salesReclamationItemEntity->getIdSalesReclamationItem(), $salesReclamationItemEntities[0]->getIdSalesReclamationItem());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesReclamationItemEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesReclamationEntity = $this->tester->createSalesReclamation($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesReclamationItemEntity = $this->tester->createSalesReclamationItem(
            $salesReclamationEntity->getIdSalesReclamation(),
            $idSalesOrderItem,
        );

        // Act
        $this->tester->getFacade()->deleteSalesReclamationItemCollection(new SalesReclamationItemCollectionDeleteCriteriaTransfer());

        // Assert
        $salesReclamationItemEntities = $this->tester->getSalesReclamationItemEntities();

        $this->assertCount(1, $salesReclamationItemEntities);
        $this->assertSame($salesReclamationItemEntity->getIdSalesReclamationItem(), $salesReclamationItemEntities[0]->getIdSalesReclamationItem());
    }
}
