<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderThreshold
 * @group Business
 * @group Facade
 * @group ReplaceSalesOrderThresholdExpensesTest
 * Add your own group annotations below this line
 */
class ReplaceSalesOrderThresholdExpensesTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_EXPENSE_TYPE
     *
     * @var string
     */
    protected const THRESHOLD_EXPENSE_TYPE = 'THRESHOLD_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const TEST_EXPENSE_TYPE = 'TEST_EXPENSE_TYPE';

    /**
     * @var \SprykerTest\Zed\SalesOrderThreshold\SalesOrderThresholdBusinessTester
     */
    protected $tester;

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
    public function testReplacesExpenseWithThresholdExpenseType(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE,
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::SUM_PRICE => 50,
            ExpenseTransfer::SUM_GROSS_PRICE => 50,
        ]);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::THRESHOLD_EXPENSE_TYPE,
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::SUM_PRICE => 100,
            ExpenseTransfer::SUM_GROSS_PRICE => 100,
        ]);
        $quoteTransfer = $this->tester->createQuoteTransferWithExpense(static::THRESHOLD_EXPENSE_TYPE, 200, 200);

        // Act
        $this->tester->getFacade()->replaceSalesOrderThresholdExpenses($quoteTransfer, $saveOrderTransfer);

        // Assert
        $expenseTransfersIndexedByType = $this->tester->getOrderSalesExpensesIndexedByType($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->assertCount(2, $expenseTransfersIndexedByType);
        $this->assertExpense($expenseTransfersIndexedByType, static::TEST_EXPENSE_TYPE, 50, 50);
        $this->assertExpense($expenseTransfersIndexedByType, static::THRESHOLD_EXPENSE_TYPE, 200, 200);
    }

    /**
     * @return void
     */
    public function testDeletesExpenseWithThresholdExpenseType(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE,
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::SUM_PRICE => 50,
            ExpenseTransfer::SUM_GROSS_PRICE => 50,
        ]);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::THRESHOLD_EXPENSE_TYPE,
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::SUM_PRICE => 100,
            ExpenseTransfer::SUM_GROSS_PRICE => 100,
        ]);
        $quoteTransfer = new QuoteTransfer();

        // Act
        $this->tester->getFacade()->replaceSalesOrderThresholdExpenses($quoteTransfer, $saveOrderTransfer);

        // Assert
        $expenseTransfersIndexedByType = $this->tester->getOrderSalesExpensesIndexedByType($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->assertCount(1, $expenseTransfersIndexedByType);
        $this->assertExpense($expenseTransfersIndexedByType, static::TEST_EXPENSE_TYPE, 50, 50);
    }

    /**
     * @return void
     */
    public function testAddsExpenseWithThresholdExpenseType(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE,
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::SUM_PRICE => 50,
            ExpenseTransfer::SUM_GROSS_PRICE => 50,
        ]);
        $quoteTransfer = $this->tester->createQuoteTransferWithExpense(static::THRESHOLD_EXPENSE_TYPE, 200, 200);

        // Act
        $this->tester->getFacade()->replaceSalesOrderThresholdExpenses($quoteTransfer, $saveOrderTransfer);

        // Assert
        $expenseTransfersIndexedByType = $this->tester->getOrderSalesExpensesIndexedByType($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->assertCount(2, $expenseTransfersIndexedByType);
        $this->assertExpense($expenseTransfersIndexedByType, static::TEST_EXPENSE_TYPE, 50, 50);
        $this->assertExpense($expenseTransfersIndexedByType, static::THRESHOLD_EXPENSE_TYPE, 200, 200);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenIdSalesOrderIsNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idSalesOrder" of transfer `Generated\Shared\Transfer\SaveOrderTransfer` is null.');

        // Act
        $this->tester->getFacade()->replaceSalesOrderThresholdExpenses(new QuoteTransfer(), new SaveOrderTransfer());
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfersIndexedByType
     * @param string $expenseType
     * @param int $expenseSumPrice
     * @param int $expenseSumGrossPrice
     *
     * @return void
     */
    protected function assertExpense(
        array $expenseTransfersIndexedByType,
        string $expenseType,
        int $expenseSumPrice,
        int $expenseSumGrossPrice
    ): void {
        $this->assertArrayHasKey($expenseType, $expenseTransfersIndexedByType);
        $this->assertSame($expenseSumPrice, $expenseTransfersIndexedByType[$expenseType]->getSumPrice());
        $this->assertSame($expenseSumGrossPrice, $expenseTransfersIndexedByType[$expenseType]->getSumGrossPrice());
    }
}
