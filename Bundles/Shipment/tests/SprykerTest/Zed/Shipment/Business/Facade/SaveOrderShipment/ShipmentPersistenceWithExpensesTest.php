<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\SaveOrderShipment;

use Exception;
use Orm\Zed\Sales\Persistence\Map\SpySalesExpenseTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesShipmentTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use BadMethodCallException;
use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SaveOrderBuilder;
use Generated\Shared\DataBuilder\SequenceNumberSettingsBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxRateQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\Tax\TaxDependencyProvider;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterCreatePlugin;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorDependencyProvider;

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
        $saveOrderTransfer = $this->tester->haveOrderWithoutShipment($quoteTransfer);

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $salesExpenseQuery = SpySalesExpenseQuery::create()->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $saveOrderTransfer);

        // Assert
        $salesShipmentEntity = $salesShipmentQuery->findOne();
        $salesExpenseEntity = $salesExpenseQuery->findOne();

        $this->assertNotNull($salesExpenseEntity, 'There is no shipment expense has been saved.');
        $this->assertEquals($salesShipmentEntity->getFkSalesExpense(), $salesExpenseEntity->getIdSalesExpense(), 'There is no expense related to shipment has been saved.');
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
        $savedOrderTransfer = $this->tester->haveOrderWithoutShipment($quoteTransfer);

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());

        $idSalesShipmentExpenseQuery = SpySalesExpenseQuery::create()
            ->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder())
            ->filterByType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE)
            ->select(SpySalesExpenseTableMap::COL_ID_SALES_EXPENSE)
            ->setFormatter(SimpleArrayFormatter::class);

        // Act
        $this->tester->getFacade()->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
        $salesShipmentEntityList = $salesShipmentQuery->find();
        $idSalesShipmentExpenseList = $idSalesShipmentExpenseQuery->find()->getData();

        $this->assertEquals($countOfNewShipmentExpenses, count($idSalesShipmentExpenseList), 'Order shipment expenses count mismatch! There is no shipment expenses have been saved.');
        foreach ($salesShipmentEntityList as $salesShipmentEntity) {
            $this->assertContains($salesShipmentEntity->getFkSalesExpense(), $idSalesShipmentExpenseList, 'Order shipment expense is not related with order shipment.');
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
            'France 1 item, expense set; expected: 1 order shipment and expense in DB' => $this->getDataWithMultipleShipmentsAnd1ItemToFranceWithExpense(),
            'France 2 items, Germany 1 item, 2 expenses set; expected: 2 order shipments and expenses in DB' => $this->getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermanyWith2Expenses(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShipmentToFranceWithExpense(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentMethodName = 'test method for split delivery';
        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod((new ShipmentMethodBuilder([
                'name' => $shipmentMethodName,
            ])));

        $expenseBuilder = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
            'unitGrossPrice' => 1111,
            'name' => $shipmentMethodName,
        ]))
            ->withAnotherShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherItem()
            ->withAnotherExpense($expenseBuilder)
            ->withAnotherShippingAddress($addressBuilder)
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
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));
        $shipmentMethodName = 'test method for SD';
        $shipmentTransfer = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod((new ShipmentMethodBuilder([
                'name' => $shipmentMethodName,
            ])))
            ->build();

        $expenseTransfer = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
            'unitGrossPrice' => 1111,
            'name' => $shipmentMethodName,
        ]))->build();
        $expenseTransfer->setShipment($shipmentTransfer);

        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
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
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $shipmentMethodName = 'test method 1 for SD';
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod((new ShipmentMethodBuilder([
                'name' => $shipmentMethodName,
            ])))
            ->build();

        $expenseTransfer1 = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
            'unitGrossPrice' => 1111,
            'name' => $shipmentMethodName,
        ]))->build();
        $expenseTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $shipmentMethodName = 'test method 2 for SD';
        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder2)
            ->withAnotherMethod((new ShipmentMethodBuilder([
                'name' => $shipmentMethodName,
            ])))
            ->build();

        $expenseTransfer2 = (new ExpenseBuilder([
            'type' => ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
            'unitGrossPrice' => 1111,
            'name' => $shipmentMethodName,
        ]))->build();
        $expenseTransfer2->setShipment($shipmentTransfer2);

        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer2);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addExpense($expenseTransfer1);
        $quoteTransfer->addItem($itemTransfer2);
        $quoteTransfer->addItem($itemTransfer3);
        $quoteTransfer->addExpense($expenseTransfer2);

        return [$quoteTransfer, 2];
    }
}