<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeBridge;
use Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeInterface;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentItemCollectorStrategyPluginInterface;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderItemCollectorPluginInterface;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group ReplaceSalesOrderItemsTest
 * Add your own group annotations below this line
 */
class ReplaceSalesOrderItemsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenOriginalOrderIsNotSetInQuoteTransfer(): void
    {
        // Arrange
        $saveOrderTransfer = (new SaveOrderTransfer())->setIdSalesOrder(1);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "originalOrder" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        $this->tester->getFacade()->replaceSalesOrderItems(new QuoteTransfer(), $saveOrderTransfer);
    }

    /**
     * @return void
     */
    public function testUsesSalesOrderAmendmentItemCollectorStrategyPluginStackStrategyForDividingItemsIntoGroups(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setIdQuote(1)->setOriginalOrder(new OrderTransfer());
        $itemTransfer1 = (new ItemTransfer())->setIdSalesOrderItem(1)->setFkSalesOrder(1);
        $itemTransfer2 = (new ItemTransfer())->setIdSalesOrderItem(2)->setFkSalesOrder(1);
        $itemTransfer3 = (new ItemTransfer())->setIdSalesOrderItem(3)->setFkSalesOrder(1);
        $salesOrderAmendmentItemCollectionTransfer = (new SalesOrderAmendmentItemCollectionTransfer())
            ->addItemToCreate($itemTransfer1)
            ->addItemToUpdate($itemTransfer2)
            ->addItemToDelete($itemTransfer3)
            ->addItemToSkip((new ItemTransfer())->setIdSalesOrderItem(4));
        $salesOrderAmendmentItemCollectorStrategyPlugin = $this->getSalesOrderAmendmentItemCollectorStrategyPluginMock();
        $salesOrderAmendmentItemCollectorStrategyPlugin->method('collect')->willReturn($salesOrderAmendmentItemCollectionTransfer);
        $salesFacadeMock = $this->getSalesFacadeMock();

        // Assert
        $salesFacadeMock->expects($this->once())
            ->method('createSalesOrderItemCollectionByQuote')
            ->with((new SalesOrderItemCollectionRequestTransfer())->setQuote($quoteTransfer)->addItem($itemTransfer1));
        $salesFacadeMock->expects($this->once())
            ->method('updateSalesOrderItemCollectionByQuote')
            ->with((new SalesOrderItemCollectionRequestTransfer())->setQuote($quoteTransfer)->addItem($itemTransfer2));
        $salesFacadeMock->expects($this->once())
            ->method('deleteSalesOrderItemCollection')
            ->with((new SalesOrderItemCollectionDeleteCriteriaTransfer())->addItem($itemTransfer3));

        // Act
        $this->tester->getFacade()->replaceSalesOrderItems($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testExecutesSalesOrderItemCollectorPlugins(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setIdQuote(1)->setOriginalOrder(new OrderTransfer());
        $salesOrderItemCollectorPlugin = $this->getSalesOrderItemCollectorPluginMock();

        // Assert
        $salesOrderItemCollectorPlugin->expects($this->once())->method('collect');

        // Act
        $this->tester->getFacade()->replaceSalesOrderItems($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testDefaultStrategyCreatesItems(): void
    {
        // Arrange
        $itemTransfer1 = $this->createDefaultItemTransfer(1);
        $itemTransfer2 = $this->createDefaultItemTransfer(2, 1, 'test_group_key_2');
        $quoteTransfer = (new QuoteTransfer())
            ->setIdQuote(1)
            ->setOriginalOrder((new OrderTransfer())->addItem($itemTransfer1))
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2);
        $salesFacadeMock = $this->getSalesFacadeMock();

        // Assert
        $salesFacadeMock->expects($this->once())
            ->method('createSalesOrderItemCollectionByQuote')
            ->with((new SalesOrderItemCollectionRequestTransfer())->setQuote($quoteTransfer)->addItem($itemTransfer2));
        $salesFacadeMock->expects($this->never())->method('updateSalesOrderItemCollectionByQuote');
        $salesFacadeMock->expects($this->never())->method('deleteSalesOrderItemCollection');

        // Act
        $this->tester->getFacade()->replaceSalesOrderItems($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testDefaultStrategyUpdatesItemsWithDifferentQuantity(): void
    {
        // Arrange
        $itemTransfer1 = $this->createDefaultItemTransfer(1);
        $itemTransfer2 = $this->createDefaultItemTransfer(2, 2);
        $quoteTransfer = (new QuoteTransfer())
            ->setIdQuote(1)
            ->setOriginalOrder((new OrderTransfer())->addItem($itemTransfer1))
            ->addItem($itemTransfer2);
        $salesFacadeMock = $this->getSalesFacadeMock();

        // Assert
        $salesFacadeMock->expects($this->once())
            ->method('updateSalesOrderItemCollectionByQuote')
            ->with((new SalesOrderItemCollectionRequestTransfer())->setQuote($quoteTransfer)->addItem($itemTransfer2));
        $salesFacadeMock->expects($this->never())->method('createSalesOrderItemCollectionByQuote');
        $salesFacadeMock->expects($this->never())->method('deleteSalesOrderItemCollection');

        // Act
        $this->tester->getFacade()->replaceSalesOrderItems($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testDefaultStrategyDeletesItems(): void
    {
        // Arrange
        $itemTransfer1 = $this->createDefaultItemTransfer(1);
        $itemTransfer2 = $this->createDefaultItemTransfer(2);
        $quoteTransfer = (new QuoteTransfer())
            ->setIdQuote(1)
            ->setOriginalOrder((new OrderTransfer())->addItem($itemTransfer1)->addItem($itemTransfer2))
            ->addItem($itemTransfer1);
        $salesFacadeMock = $this->getSalesFacadeMock();

        // Assert
        $salesFacadeMock->expects($this->once())
            ->method('deleteSalesOrderItemCollection')
            ->with((new SalesOrderItemCollectionDeleteCriteriaTransfer())->addItem($itemTransfer2));
        $salesFacadeMock->expects($this->never())->method('createSalesOrderItemCollectionByQuote');
        $salesFacadeMock->expects($this->never())->method('updateSalesOrderItemCollectionByQuote');

        // Act
        $this->tester->getFacade()->replaceSalesOrderItems($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testDefaultStrategyDoesCreatesUpdatesAndDeletesItems(): void
    {
        // Arrange
        $itemTransfer1 = $this->createDefaultItemTransfer(1);
        $itemTransfer2 = $this->createDefaultItemTransfer(2, 1, 'test_group_key_2');
        $itemTransfer3 = $this->createDefaultItemTransfer(3, 1, 'test_group_key_3');
        $itemTransfer4 = $this->createDefaultItemTransfer(4, 2, 'test_group_key_3');
        $itemTransfer5 = $this->createDefaultItemTransfer(5, 2, 'test_group_key_4');
        $quoteTransfer = (new QuoteTransfer())
            ->setIdQuote(1)
            ->setOriginalOrder((new OrderTransfer())->addItem($itemTransfer1)->addItem($itemTransfer3)->addItem($itemTransfer5))
            ->addItem($itemTransfer1)
            ->addItem($itemTransfer2)
            ->addItem($itemTransfer4);
        $salesFacadeMock = $this->getSalesFacadeMock();

        // Assert
        $salesFacadeMock->expects($this->once())
            ->method('createSalesOrderItemCollectionByQuote')
            ->with((new SalesOrderItemCollectionRequestTransfer())->setQuote($quoteTransfer)->addItem($itemTransfer2));
        $salesFacadeMock->expects($this->once())
            ->method('updateSalesOrderItemCollectionByQuote')
            ->with((new SalesOrderItemCollectionRequestTransfer())->setQuote($quoteTransfer)->addItem($itemTransfer4));
        $salesFacadeMock->expects($this->once())
            ->method('deleteSalesOrderItemCollection')
            ->with((new SalesOrderItemCollectionDeleteCriteriaTransfer())->addItem($itemTransfer5));

        // Act
        $this->tester->getFacade()->replaceSalesOrderItems($quoteTransfer, new SaveOrderTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentItemCollectorStrategyPluginInterface
     */
    protected function getSalesOrderAmendmentItemCollectorStrategyPluginMock(): SalesOrderAmendmentItemCollectorStrategyPluginInterface
    {
        $salesOrderAmendmentItemCollectorStrategyPlugin = $this->getMockBuilder(
            SalesOrderAmendmentItemCollectorStrategyPluginInterface::class,
        )->getMock();
        $salesOrderAmendmentItemCollectorStrategyPlugin->method('isApplicable')->willReturn(true);
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_AMENDMENT_ITEM_COLLECTOR_STRATEGY,
            [$salesOrderAmendmentItemCollectorStrategyPlugin],
        );

        return $salesOrderAmendmentItemCollectorStrategyPlugin;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderItemCollectorPluginInterface
     */
    protected function getSalesOrderItemCollectorPluginMock(): SalesOrderItemCollectorPluginInterface
    {
        $salesOrderItemCollectorPlugin = $this->getMockBuilder(
            SalesOrderItemCollectorPluginInterface::class,
        )->getMock();
        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::PLUGINS_SALES_ORDER_ITEM_COLLECTOR_PLUGIN,
            [$salesOrderItemCollectorPlugin],
        );

        return $salesOrderItemCollectorPlugin;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderAmendment\Dependency\Facade\SalesOrderAmendmentToSalesFacadeInterface
     */
    protected function getSalesFacadeMock(): SalesOrderAmendmentToSalesFacadeInterface
    {
        $salesFacadeMock = $this
            ->getMockBuilder(SalesOrderAmendmentToSalesFacadeBridge::class)
            ->onlyMethods(['createSalesOrderItemCollectionByQuote', 'updateSalesOrderItemCollectionByQuote', 'deleteSalesOrderItemCollection'])
            ->setConstructorArgs([$this->tester->getLocator()->sales()->facade()])
            ->getMock();

        $this->tester->setDependency(
            SalesOrderAmendmentDependencyProvider::FACADE_SALES,
            $salesFacadeMock,
        );

        return $salesFacadeMock;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItemForOrder(SaveOrderTransfer $saveOrderTransfer): SpySalesOrderItem
    {
        return $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), [
            'created_at' => '2025-01-01 00:00:00',
            'tax_rate' => '10.00',
            'quantity' => 1,
        ]);
    }

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     * @param string $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createDefaultItemTransfer(
        int $idSalesOrderItem,
        int $quantity = 1,
        string $groupKey = 'test_group_key_1'
    ): ItemTransfer {
        return (new ItemTransfer())->setIdSalesOrderItem($idSalesOrderItem)
            ->setFkSalesOrder(1)
            ->setGroupKey($groupKey)
            ->setQuantity($quantity);
    }
}
