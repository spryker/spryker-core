<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemServicePointTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerTest\Zed\SalesServicePoint\SalesServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesServicePoint
 * @group Business
 * @group Facade
 * @group ExpandOrderItemsWithServicePointTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithServicePointTest extends Unit
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
    public function testShouldExpandOrderItemsWithServicePoint(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->withServicePoint()->build();
        $saveOrderTransfer = $this->createOrderWithItem($itemTransfer);
        $orderItemTransfers = (array)$saveOrderTransfer->getOrderItems();
        $persistedSalesOrderItemServicePointTransfer = $this->tester->haveSalesOrderItemServicePoint([
            SalesOrderItemServicePointTransfer::ID_SALES_ORDER_ITEM => $orderItemTransfers[0]->getIdSalesOrderItemOrFail(),
            SalesOrderItemServicePointTransfer::NAME => $itemTransfer->getServicePointOrFail()->getName(),
            SalesOrderItemServicePointTransfer::KEY => $itemTransfer->getServicePointOrFail()->getKeyOrFail(),
        ]);

        // Act
        $itemsTransfers = $this->tester->getFacade()->expandOrderItemsWithServicePoint($orderItemTransfers);

        // Assert
        $salesOrderItemServicePointTransfer = $itemsTransfers[0]->getSalesOrderItemServicePoint();

        $this->assertSame(
            $persistedSalesOrderItemServicePointTransfer->getNameOrFail(),
            $salesOrderItemServicePointTransfer->getNameOrFail(),
        );
        $this->assertSame(
            $persistedSalesOrderItemServicePointTransfer->getKeyOrFail(),
            $salesOrderItemServicePointTransfer->getKeyOrFail(),
        );
        $this->assertSame(
            $persistedSalesOrderItemServicePointTransfer->getIdSalesOrderItemOrFail(),
            $salesOrderItemServicePointTransfer->getIdSalesOrderItemOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotExpandOrderItemsWithServicePoint(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->build();
        $saveOrderTransfer = $this->createOrderWithItem($itemTransfer);

        // Act
        $itemsTransfers = $this->tester->getFacade()->expandOrderItemsWithServicePoint((array)$saveOrderTransfer->getOrderItems());

        // Assert
        $this->assertEmpty($itemsTransfers[0]->getSalesOrderItemServicePoint());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createOrderWithItem(ItemTransfer $itemTransfer): SaveOrderTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemTransfer->toArray())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
    }
}
