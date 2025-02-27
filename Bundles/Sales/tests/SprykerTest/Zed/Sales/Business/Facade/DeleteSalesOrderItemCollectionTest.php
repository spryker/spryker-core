<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group DeleteSalesOrderItemCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderItemCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testExecutesSalesOrderItemCollectionPreDeletePluginsWhenItemsConditionIsSet(): void
    {
        // Assert
        $this->getSalesOrderItemCollectionPreDeletePluginMock()->expects($this->once())->method('preDelete');
        $salesOrderItemCollectionDeleteCriteriaTransfer = (new SalesOrderItemCollectionDeleteCriteriaTransfer())
            ->addItem((new ItemTransfer())->setIdSalesOrderItem(123456));

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemCollection($salesOrderItemCollectionDeleteCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNotExecuteSalesOrderItemCollectionPreDeletePluginsWhenConditionsAreNotSet(): void
    {
        // Assert
        $this->getSalesOrderItemCollectionPreDeletePluginMock()->expects($this->never())->method('preDelete');

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemCollection(new SalesOrderItemCollectionDeleteCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testDeletesSalesOrderItemBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $this->tester->clearSalesOrderItemOmsHistory($idSalesOrderItemToDelete);
        $salesOrderItemCollectionDeleteCriteriaTransfer = (new SalesOrderItemCollectionDeleteCriteriaTransfer())
            ->addItem((new ItemTransfer())->setIdSalesOrderItem($idSalesOrderItemToDelete));

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemCollection($salesOrderItemCollectionDeleteCriteriaTransfer);

        // Assert
        $itemTransfers = $this->tester->getSalesOrderItems([$idSalesOrderItemToDelete, $salesOrderItemEntity->getIdSalesOrderItem()]);

        $this->assertCount(1, $itemTransfers);
        $this->assertSame($salesOrderItemEntity->getIdSalesOrderItem(), $itemTransfers[0]->getIdSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemsWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemCollection(new SalesOrderItemCollectionDeleteCriteriaTransfer());

        // Assert
        $itemTransfers = $this->tester->getSalesOrderItems([$idSalesOrderItemToDelete, $salesOrderItemEntity->getIdSalesOrderItem()]);

        $this->assertCount(2, $itemTransfers);
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderItemsWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail());
        $idSalesOrderItemToDelete = $saveOrderTransfer->getOrderItems()->getIterator()->current()->getIdSalesOrderItemOrFail();
        $salesOrderItemCollectionDeleteCriteriaTransfer = (new SalesOrderItemCollectionDeleteCriteriaTransfer())
            ->addItem((new ItemTransfer())->setIdSalesOrderItem(-1));

        // Act
        $this->tester->getFacade()->deleteSalesOrderItemCollection($salesOrderItemCollectionDeleteCriteriaTransfer);

        // Assert
        $itemTransfers = $this->tester->getSalesOrderItems([$idSalesOrderItemToDelete, $salesOrderItemEntity->getIdSalesOrderItem()]);

        $this->assertCount(2, $itemTransfers);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface
     */
    protected function getSalesOrderItemCollectionPreDeletePluginMock(): SalesOrderItemCollectionPreDeletePluginInterface
    {
        $salesOrderItemCollectionPreDeletePlugin = $this->getMockBuilder(
            SalesOrderItemCollectionPreDeletePluginInterface::class,
        )->getMock();
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_SALES_ORDER_ITEM_COLLECTION_PRE_DELETE,
            [$salesOrderItemCollectionPreDeletePlugin],
        );

        return $salesOrderItemCollectionPreDeletePlugin;
    }
}
