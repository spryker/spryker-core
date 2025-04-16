<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\SalesExpenseCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group DeleteSalesExpenseCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesExpenseCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const TEST_EXPENSE_TYPE_1 = 'test_expense_type_1';

    /**
     * @var string
     */
    protected const TEST_EXPENSE_TYPE_2 = 'test_expense_type_2';

    /**
     * @var string
     */
    protected const TEST_EXPENSE_TYPE_3 = 'test_expense_type_3';

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
    public function testDeletesExpensesBySalesOrderIdsWhenTheCorrespondingExpensesExist(): void
    {
        // Arrange
        // Get the current count of expenses to be used later for comparison
        $expenseTransfersFromDbBeforeTest = $this->tester->getSalesExpenses();

        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer3 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $this->tester->haveSalesExpense([ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrderOrFail()]);

        $expenseTransfer1 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
        ]);

        $expenseTransfer2 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
        ]);

        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrder($saveOrderTransfer1->getIdSalesOrderOrFail())
            ->addIdSalesOrder($saveOrderTransfer3->getIdSalesOrderOrFail());

        // Act
        $this->tester->getFacade()->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        // Assert
        $expenseTransfersFromDbAfterTest = $this->tester->getSalesExpenses();
        $this->assertCount(count($expenseTransfersFromDbBeforeTest) + 2, $expenseTransfersFromDbAfterTest);

        $salesExpenseIds = $this->tester->extractSalesExpenseIds($expenseTransfersFromDbAfterTest);
        $this->assertContains($expenseTransfer1->getIdSalesExpenseOrFail(), $salesExpenseIds);
        $this->assertContains($expenseTransfer2->getIdSalesExpenseOrFail(), $salesExpenseIds);
    }

    /**
     * @return void
     */
    public function testReturnsDeletedSalesExpensesInResponse(): void
    {
        // Arrange
        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $expenseTransfer1 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrderOrFail(),
        ]);

        $expenseTransfer2 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
        ]);

        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrder($saveOrderTransfer1->getIdSalesOrderOrFail())
            ->addIdSalesOrder($saveOrderTransfer2->getIdSalesOrderOrFail());

        // Act
        $salesExpenseCollectionResponseTransfer = $this->tester->getFacade()->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(2, $salesExpenseCollectionResponseTransfer->getSalesExpenses());

        $deletedExpenseIds = array_map(function ($expenseTransfer) {
            return $expenseTransfer->getIdSalesExpense();
        }, $salesExpenseCollectionResponseTransfer->getSalesExpenses()->getArrayCopy());

        $this->assertContains($expenseTransfer1->getIdSalesExpenseOrFail(), $deletedExpenseIds);
        $this->assertContains($expenseTransfer2->getIdSalesExpenseOrFail(), $deletedExpenseIds);
    }

    /**
     * @return void
     */
    public function testDeletesExpensesBySalesOrderIdsWhenSomeOfSpecifiedExpensesExist(): void
    {
        // Arrange
        // Get the current count of expenses to be used later for comparison
        $expenseTransfersFromDbBeforeTest = $this->tester->getSalesExpenses();

        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrderOrFail(),
        ]);

        $expenseTransfer = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
        ]);

        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrder($saveOrderTransfer1->getIdSalesOrderOrFail())
            ->addIdSalesOrder($saveOrderTransfer1->getIdSalesOrderOrFail() + $saveOrderTransfer2->getIdSalesOrderOrFail());

        // Act
        $this->tester->getFacade()->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        // Assert
        $expenseTransfersFromDbAfterTest = $this->tester->getSalesExpenses();
        $this->assertCount(count($expenseTransfersFromDbBeforeTest) + 1, $expenseTransfersFromDbAfterTest);

        $salesExpenseIds = $this->tester->extractSalesExpenseIds($expenseTransfersFromDbAfterTest);
        $this->assertContains($expenseTransfer->getIdSalesExpenseOrFail(), $salesExpenseIds);
    }

    /**
     * @return void
     */
    public function testDeletesExpensesByTypesWhenTheCorrespondingExpensesExist(): void
    {
        // Arrange
        // Get the current count of expenses to be used later for comparison
        $expenseTransfersFromDbBeforeTest = $this->tester->getSalesExpenses();

        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_1,
        ]);

        $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_1,
        ]);

        $expenseTransfer = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_2,
        ]);

        $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_3,
        ]);

        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addType(static::TEST_EXPENSE_TYPE_1)
            ->addType(static::TEST_EXPENSE_TYPE_3);

        // Act
        $this->tester->getFacade()->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        // Assert
        $expenseTransfersFromDbAfterTest = $this->tester->getSalesExpenses();
        $this->assertCount(count($expenseTransfersFromDbBeforeTest) + 1, $expenseTransfersFromDbAfterTest);

        $salesExpenseIds = $this->tester->extractSalesExpenseIds($expenseTransfersFromDbAfterTest);
        $this->assertContains($expenseTransfer->getIdSalesExpenseOrFail(), $salesExpenseIds);
    }

    /**
     * @return void
     */
    public function testDeletesExpensesByTypesWhenSomeOfSpecifiedExpensesExist(): void
    {
        // Arrange
        // Get the current count of expenses to be used later for comparison
        $expenseTransfersFromDbBeforeTest = $this->tester->getSalesExpenses();

        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_1,
        ]);

        $expenseTransfer = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_2,
        ]);

        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addType(static::TEST_EXPENSE_TYPE_1)
            ->addType(static::TEST_EXPENSE_TYPE_3);

        // Act
        $this->tester->getFacade()->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        // Assert
        $expenseTransfersFromDbAfterTest = $this->tester->getSalesExpenses();
        $this->assertCount(count($expenseTransfersFromDbBeforeTest) + 1, $expenseTransfersFromDbAfterTest);

        $salesExpenseIds = $this->tester->extractSalesExpenseIds($expenseTransfersFromDbAfterTest);
        $this->assertContains($expenseTransfer->getIdSalesExpenseOrFail(), $salesExpenseIds);
    }

    /**
     * @return void
     */
    public function testDeletesExpensesByBothExpenseIdsAndTypesWhenTheCorrespondingExpensesExist(): void
    {
        // Arrange
        // Get the current count of expenses to be used later for comparison
        $expenseTransfersFromDbBeforeTest = $this->tester->getSalesExpenses();

        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer3 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        $expenseTransfer1 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_2,
        ]);

        $expenseTransfer2 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_1,
        ]);

        $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_2,
        ]);

        $expenseTransfer3 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer3->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_3,
        ]);

        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrder($saveOrderTransfer2->getIdSalesOrderOrFail())
            ->addType(static::TEST_EXPENSE_TYPE_2);

        // Act
        $this->tester->getFacade()->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        // Assert
        $expenseTransfersFromDbAfterTest = $this->tester->getSalesExpenses();
        $this->assertCount(count($expenseTransfersFromDbBeforeTest) + 3, $expenseTransfersFromDbAfterTest);

        $salesExpenseIds = $this->tester->extractSalesExpenseIds($expenseTransfersFromDbAfterTest);
        $this->assertContains($expenseTransfer1->getIdSalesExpenseOrFail(), $salesExpenseIds);
        $this->assertContains($expenseTransfer2->getIdSalesExpenseOrFail(), $salesExpenseIds);
        $this->assertContains($expenseTransfer3->getIdSalesExpenseOrFail(), $salesExpenseIds);
    }

    /**
     * @return void
     */
    public function testDeletesAllExpensesWhenNoCriteriaPropertiesAreProvided(): void
    {
        $this->markTestSkipped('This test is skipped because it is not possible to delete all expenses without any criteria. The test was only successful because of all data was removed from the database recursively in the setUp method.');

        // Arrange
        // Get the current count of expenses to be used later for comparison
        $expenseTransfersFromDbBeforeTest = $this->tester->getSalesExpenses();

        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrderOrFail(),
        ]);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
        ]);
        $salesExpenseCollectionDeleteCriteriaTransfer = new SalesExpenseCollectionDeleteCriteriaTransfer();

        // Act
        $this->tester->getFacade()->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        // Assert
        $expenseTransfersFromDbAfterTestAfterTest = $this->tester->getSalesExpenses();
        $this->assertSame($expenseTransfersFromDbBeforeTest, $expenseTransfersFromDbAfterTestAfterTest);
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteAnyExpensesWhenSpecifiedExpensesDoesNotExist(): void
    {
        // Arrange
        // Get the current count of expenses to be used later for comparison
        $expenseTransfersFromDbBeforeTest = $this->tester->getSalesExpenses();

        $saveOrderTransfer1 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $saveOrderTransfer2 = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $expenseTransfer1 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer1->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_1,
        ]);
        $expenseTransfer2 = $this->tester->haveSalesExpense([
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer2->getIdSalesOrderOrFail(),
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE_1,
        ]);
        $salesExpenseCollectionDeleteCriteriaTransfer = (new SalesExpenseCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrder($saveOrderTransfer1->getIdSalesOrderOrFail() + 1)
            ->addType(static::TEST_EXPENSE_TYPE_2);

        // Act
        $this->tester->getFacade()->deleteSalesExpenseCollection($salesExpenseCollectionDeleteCriteriaTransfer);

        // Assert
        $expenseTransfersFromDbAfterTest = $this->tester->getSalesExpenses();

        $expectedCountAfterTest = count($expenseTransfersFromDbBeforeTest) + 2;
        $this->assertCount($expectedCountAfterTest, $expenseTransfersFromDbAfterTest);

        $salesExpenseIds = $this->tester->extractSalesExpenseIds($expenseTransfersFromDbAfterTest);
        $this->assertContains($expenseTransfer1->getIdSalesExpenseOrFail(), $salesExpenseIds);
        $this->assertContains($expenseTransfer2->getIdSalesExpenseOrFail(), $salesExpenseIds);
    }
}
