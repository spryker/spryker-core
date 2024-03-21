<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\SalesServicePoint\SalesServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesServicePoint
 * @group Business
 * @group Facade
 * @group SaveSalesOrderItemServicePointsFromQuoteTest
 * Add your own group annotations below this line
 */
class SaveSalesOrderItemServicePointsFromQuoteTest extends Unit
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
    }

    /**
     * @return void
     */
    public function testShouldNotPersistAnyServicePointsForItemsFromQuote(): void
    {
        // Arrange
        $this->tester->ensureSalesOrderItemServicePointDatabaseTableIsEmpty();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $quoteTransfer->setStore($storeTransfer);

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $this->tester->getFacade()->saveSalesOrderItemServicePointsFromQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getSalesOrderItemServicePointQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldPersistOneServicePointForItemsFromQuote(): void
    {
        // Arrange
        $this->tester->ensureSalesOrderItemServicePointDatabaseTableIsEmpty();
        $itemBuilder = (new ItemBuilder())->withServicePoint();
        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemBuilder)
            ->withAnotherItem()
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $quoteTransfer->setStore($storeTransfer);

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $this->tester->getFacade()->saveSalesOrderItemServicePointsFromQuote($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getSalesOrderItemServicePointQuery()->count());
    }
}
