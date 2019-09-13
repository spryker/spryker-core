<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\SaveOrderShipment;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesExpenseTableMap;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Shared\Shipment\ShipmentConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group SaveOrderShipment
 * @group ShipmentPersistenceWithExpensesTest
 * Add your own group annotations below this line
 */
class ShipmentPersistenceWithExpensesTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteLevelShipmentAndExpenseDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteLevelShipmentAndExpense(
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $salesExpenseQuery = SpySalesExpenseQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesShipmentEntity = $salesShipmentQuery->findOne();
        $salesExpenseEntity = $salesExpenseQuery->findOne();

        $this->assertNotNull($salesExpenseEntity, 'Shipment expense should have been saved.');
        $this->assertEquals($salesShipmentEntity->getFkSalesExpense(), $salesExpenseEntity->getIdSalesExpense(), 'Shipment expense ID should have been connected to shipment entity.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $countOfNewShipmentExpenses
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipmentExpenses
    ): void {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithoutShipment($quoteTransfer);

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $idSalesShipmentExpenseQuery = SpySalesExpenseQuery::create()
            ->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->filterByType(ShipmentConfig::SHIPMENT_EXPENSE_TYPE)
            ->select(SpySalesExpenseTableMap::COL_ID_SALES_EXPENSE)
            ->setFormatter(SimpleArrayFormatter::class);

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesShipmentEntities = $salesShipmentQuery->find();
        $idSalesShipmentExpenseList = $idSalesShipmentExpenseQuery->find()->getData();

        $this->assertCount($countOfNewShipmentExpenses, $idSalesShipmentExpenseList, 'Expected number of shipment expenses does not match the actual number.');
        foreach ($salesShipmentEntities as $i => $salesShipmentEntity) {
            $this->assertContains($salesShipmentEntity->getFkSalesExpense(), $idSalesShipmentExpenseList, sprintf('Shipment expense ID should have been connected to shipment entity (iteration #%d).', $i));
        }
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteLevelShipmentAndExpenseDataProvider(): array
    {
        return [
            'any data, expense set; expected: shipment and expense in DB' => $this->getDataWithQuoteLevelShipmentToFranceWithExpense(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentExpensesDataProvider(): array
    {
        return [
            'France 1 item, 1 expense set; expected: 1 order shipment and expense in DB' => $this->getDataWithMultipleShipmentsAnd1ItemToFranceWithExpense(),
            'France 2 items, Germany 1 item, 2 expenses set; expected: 2 order shipments and expenses in DB' => $this->getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermanyWith2Expenses(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShipmentToFranceWithExpense(): array
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod();

        $expenseBuilder = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
        ]))->withShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withShipment($shipmentBuilder)
            ->withItem()
            ->withExpense($expenseBuilder)
            ->withShippingAddress($addressBuilder)
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd1ItemToFranceWithExpense(): array
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));
        $shipmentTransfer = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod()
            ->build();

        $expenseTransfer = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
        ]))->build();
        $expenseTransfer->setShipment($shipmentTransfer);

        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer);
        $quoteTransfer->addExpense($expenseTransfer);

        return [$quoteTransfer, 1];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermanyWith2Expenses(): array
    {
        $addressBuilder1 = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder1)
            ->withMethod()
            ->build();

        $addressBuilder2 = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'DE']));
        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder2)
            ->withMethod()
            ->build();

        $expenseTransfer1 = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
        ]))->build();
        $expenseTransfer1->setShipment($shipmentTransfer1);

        $expenseTransfer2 = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
        ]))->build();
        $expenseTransfer2->setShipment($shipmentTransfer2);

        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer2);

        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);
        $quoteTransfer->addItem($itemTransfer3);
        $quoteTransfer->addExpense($expenseTransfer1);
        $quoteTransfer->addExpense($expenseTransfer2);

        return [$quoteTransfer, 2];
    }
}
