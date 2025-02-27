<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConfiguration\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConfiguration
 * @group Business
 * @group Facade
 * @group DeleteSalesOrderItemConfigurationCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderItemConfigurationCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesProductConfiguration\SalesProductConfigurationBusinessTester
     */
    protected SalesProductConfigurationBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemConfigurationDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testDeleteSalesOrderItemConfigurationEntitiesBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->createSalesOrderItemConfiguration($idSalesOrderItemToDelete);
        $salesOrderItemConfigurationEntity = $this->tester->createSalesOrderItemConfiguration($salesOrderItemEntity->getIdSalesOrderItem());
        $salesOrderItemConfigurationCollectionDeleteCriteriaTransfer = (new SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($idSalesOrderItemToDelete);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemConfigurationCollection($salesOrderItemConfigurationCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemConfigurationEntities = $this->tester->getSalesOrderItemConfigurationEntities();

        $this->assertCount(1, $salesOrderItemConfigurationEntities);
        $this->assertSame($salesOrderItemConfigurationEntity->getIdSalesOrderItemConfiguration(), $salesOrderItemConfigurationEntities[0]->getIdSalesOrderItemConfiguration());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemConfigurationEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemConfigurationEntity = $this->tester->createSalesOrderItemConfiguration($idSalesOrderItem);
        $salesOrderItemConfigurationCollectionDeleteCriteriaTransfer = (new SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemConfigurationCollection($salesOrderItemConfigurationCollectionDeleteCriteriaTransfer);

        // Assert
        $salesOrderItemConfigurationEntities = $this->tester->getSalesOrderItemConfigurationEntities();

        $this->assertCount(1, $salesOrderItemConfigurationEntities);
        $this->assertSame($salesOrderItemConfigurationEntity->getIdSalesOrderItemConfiguration(), $salesOrderItemConfigurationEntities[0]->getIdSalesOrderItemConfiguration());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemConfigurationEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $idSalesOrderItem = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemConfigurationEntity = $this->tester->createSalesOrderItemConfiguration($idSalesOrderItem);

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemConfigurationCollection(new SalesOrderItemConfigurationCollectionDeleteCriteriaTransfer());

        // Assert
        $salesOrderItemConfigurationEntities = $this->tester->getSalesOrderItemConfigurationEntities();

        $this->assertCount(1, $salesOrderItemConfigurationEntities);
        $this->assertSame($salesOrderItemConfigurationEntity->getIdSalesOrderItemConfiguration(), $salesOrderItemConfigurationEntities[0]->getIdSalesOrderItemConfiguration());
    }
}
