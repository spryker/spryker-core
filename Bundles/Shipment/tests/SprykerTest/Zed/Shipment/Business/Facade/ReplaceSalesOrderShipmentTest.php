<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\Shipment\ShipmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group ReplaceSalesOrderShipmentTest
 * Add your own group annotations below this line
 */
class ReplaceSalesOrderShipmentTest extends Unit
{
    /**
     * @var string
     */
    protected const DUMMY_PAYMENT_STATE_MACHINE_PROCESS_NAME = 'DummyPayment01';

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     *
     * @var string
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const TEST_EXPENSE_TYPE = 'TEST_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_METHOD_NAME = 'test shipment method name';

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected ShipmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testThrowsRequiredTransferPropertyExceptionWhenIdSalesOrderIsNotSetInSaveOrderTransfer(): void
    {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "idSalesOrder" for transfer Generated\Shared\Transfer\SaveOrderTransfer.');

        // Act
        $this->tester->getFacade()->replaceSalesOrderShipment(new QuoteTransfer(), new SaveOrderTransfer());
    }

    /**
     * @return void
     */
    public function testReplacesSalesShipment(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $quoteTransfer->getItems()->offsetGet(0)->getShipment()->getMethod()->setName(static::TEST_SHIPMENT_METHOD_NAME);
        $quoteTransfer->getItems()->offsetUnset(1);

        $saveOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);
        $this->tester->setShipmentToSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail());

        // Act
        $this->tester->getFacade()->replaceSalesOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $this->assertSame(static::TEST_SHIPMENT_METHOD_NAME, $this->tester->findSalesShipmentEntity($saveOrderTransfer->getIdSalesOrder())->getName());
    }

    /**
     * @return void
     */
    public function testReplacesExpensesWithShipmentExpenseType(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $quoteTransfer->addExpense((new ExpenseTransfer())
            ->setShipment($quoteTransfer->getItems()->offsetGet(0)->getShipment())
            ->setType(static::SHIPMENT_EXPENSE_TYPE)
            ->setSumPrice(200)
            ->setSumGrossPrice(200));
        $saveOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::TEST_EXPENSE_TYPE,
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::SUM_PRICE => 50,
            ExpenseTransfer::SUM_GROSS_PRICE => 50,
        ]);
        $this->tester->haveSalesExpense([
            ExpenseTransfer::TYPE => static::SHIPMENT_EXPENSE_TYPE,
            ExpenseTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            ExpenseTransfer::SUM_PRICE => 100,
            ExpenseTransfer::SUM_GROSS_PRICE => 100,
        ]);
        $this->tester->setShipmentToSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail());

        // Act
        $this->tester->getFacade()->replaceSalesOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $expenseTransfersIndexedByType = $this->tester->getOrderSalesExpensesIndexedByType($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->assertCount(2, $expenseTransfersIndexedByType);
        $this->assertExpense($expenseTransfersIndexedByType, static::TEST_EXPENSE_TYPE, 50, 50);
        $this->assertExpense($expenseTransfersIndexedByType, static::SHIPMENT_EXPENSE_TYPE, 200, 200);
    }

    /**
     * @return void
     */
    public function testUnsetsFkSalesShipmentForSalesOrderItems(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);
        $this->tester->setShipmentToSalesOrder($saveOrderTransfer->getIdSalesOrderOrFail());

        // Act
        $this->tester->getFacade()->replaceSalesOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Arrange
        $this->assertNull($this->tester->findSalesOrderItemEntity($saveOrderTransfer->getIdSalesOrderOrFail())->getFkSalesShipment());
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
