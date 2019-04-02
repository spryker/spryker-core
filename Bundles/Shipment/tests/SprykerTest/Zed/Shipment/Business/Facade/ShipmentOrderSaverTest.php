<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade;

use Exception;
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
 * @group ShipmentOrderSaverTest
 * Add your own group annotations below this line
 */
class ShipmentOrderSaverTest extends Test
{
    protected const FLOAT_COMPARISION_DELTA = 0.001;

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteShipment(
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
        $expectedOrderShipmentCount = $countShipmentsBefore + 1;
        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no shipment has been saved.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteShipmentAndExpenseDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteShipmentAndExpense(
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $salesExpenseQuery = SpySalesExpenseQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();
        $countExpensesBefore = $salesExpenseQuery->count();

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
        $expectedOrderShipmentCount = $countShipmentsBefore + 1;
        $expectedOrderShipmentExpenseCount = $countExpensesBefore + 1;
        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no any shipment has been saved.');
        $this->assertEquals($expectedOrderShipmentExpenseCount, $salesExpenseQuery->count(), 'Order shipment expenses count mismatch! There is no any shipment expense has been saved.');
        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->filterByFkSalesExpense(null, Criteria::ISNOTNULL)->count(), 'Order expenses related to shipments count mismatch! There is no expenses related to shipments have been saved.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteShipmentAndShippingAddressDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteShipmentAndShippingAddress(
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesAddressQuery = SpySalesOrderAddressQuery::create();

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
        $expectedOrderShipmentCount = $countShipmentsBefore + 1;
        $expectedOrderShipmentEntity = $salesShipmentQuery->findOne();
        $expectedOrderAddressEntity = $salesAddressQuery
            ->useSpySalesShipmentQuery()
            ->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder())
            ->endUse()
            ->findOne();
        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no any shipment has been saved.');
        $this->assertNotNull($expectedOrderShipmentEntity, 'Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertNotNull($expectedOrderAddressEntity, 'Order address could not be found! There is no any order address has been saved.');
        $this->assertEquals($expectedOrderAddressEntity->getIdSalesOrderAddress(), $expectedOrderShipmentEntity->getFkSalesOrderAddress(), 'Order shipment has no any related addresses been saved.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseQuoteShipmentAndItemsDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseQuoteShipmentAndItems(
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesAddressQuery = SpySalesOrderAddressQuery::create();

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
        $expectedOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $expectedOrderShipmentCount = $countShipmentsBefore + 1;
        $expectedOrderShipmentEntity = $salesShipmentQuery->findOne();
        $expectedOrderAddressEntity = $salesAddressQuery
            ->useSpySalesShipmentQuery()
            ->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder())
            ->endUse()
            ->findOne();

        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no any shipment has been saved.');
//        $this->assertNotNull($expectedOrderEntity->getShippingAddress(), '!Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertNotNull($expectedOrderShipmentEntity, 'Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertNotNull($expectedOrderAddressEntity, 'Order address could not be found! There is no any order address has been saved.');
        $this->assertEquals($expectedOrderAddressEntity->getIdSalesOrderAddress(), $expectedOrderShipmentEntity->getFkSalesOrderAddress(), 'Order shipment has no any related addresses been saved.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersistedDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $expectedCountOfShipments
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersisted(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipments
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();

        $salesExpenseQuery = SpySalesExpenseQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countExpensesBefore = $salesExpenseQuery->count();

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
//        $expectedOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $expectedOrderShipmentCount = $countShipmentsBefore + $countOfNewShipments;
        $expectedOrderShipmentEntity = $salesShipmentQuery->findOne();

        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no any shipment has been saved.');
//        $this->assertNotNull($expectedOrderEntity->getShippingAddress(), '!Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertNotNull($expectedOrderShipmentEntity, 'Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertEquals($countExpensesBefore, $salesExpenseQuery->count(), 'Order shipment expenses count mismatch! There is no any shipment expense should be saved.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentExpensesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $expectedCountOfShipments
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentExpenses(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipments
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();

        $salesExpenseQuery = SpySalesExpenseQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countExpensesBefore = $salesExpenseQuery->count();

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
//        $expectedOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $expectedOrderShipmentCount = $countShipmentsBefore + $countOfNewShipments;
        $expectedOrderShipmentExpenseCount = $countExpensesBefore + $countOfNewShipments;
        $expectedOrderShipmentEntity = $salesShipmentQuery->findOne();

        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no any shipment has been saved.');
//        $this->assertNotNull($expectedOrderEntity->getShippingAddress(), '!Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertNotNull($expectedOrderShipmentEntity, 'Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertEquals($expectedOrderShipmentExpenseCount, $salesExpenseQuery->count(), 'Order shipment expenses count mismatch! There is no any shipment expense has been saved.');
        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->filterByFkSalesExpense(null, Criteria::ISNOTNULL)->count(), 'Order expenses related to shipments count mismatch! There is no expenses related to shipments have been saved.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentAddressesDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $expectedCountOfShipments
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentAddresses(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipments
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();

        $salesAddressQuery = SpySalesOrderAddressQuery::create();
        $expectedOrderAddressEntityQuery = $salesAddressQuery
            ->useSpySalesShipmentQuery()
            ->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder())
            ->endUse();

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
//        $expectedOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $expectedOrderShipmentCount = $countShipmentsBefore + $countOfNewShipments;
        $expectedOrderShipmentEntity = $salesShipmentQuery->findOne();
        $expectedOrderAddressEntity = $expectedOrderAddressEntityQuery->findOne();

        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no any shipment has been saved.');
//        $this->assertNotNull($expectedOrderEntity->getShippingAddress(), '!Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertNotNull($expectedOrderShipmentEntity, 'Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertNotNull($expectedOrderAddressEntity, 'Order address could not be found! There is no any order address has been saved.');
        $this->assertEquals($expectedOrderAddressEntity->getIdSalesOrderAddress(), $expectedOrderShipmentEntity->getFkSalesOrderAddress(), 'Order shipment has no any related addresses been saved.');
    }

    /**
     * @todo Is this test duplicate for testShipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersisted?
     *
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithItemsDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $expectedCountOfShipments
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithItems(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipments
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();

        $salesExpenseQuery = SpySalesExpenseQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countExpensesBefore = $salesExpenseQuery->count();

        $shipmentFacade = $this->tester->getFacade();

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
//        $expectedOrderEntity = SpySalesOrderQuery::create()->findOneByIdSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $expectedOrderShipmentCount = $countShipmentsBefore + $countOfNewShipments;
        $expectedOrderShipmentEntity = $salesShipmentQuery->findOne();

        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'Order shipments count mismatch! There is no any shipment has been saved.');
//        $this->assertNotNull($expectedOrderEntity->getShippingAddress(), '!Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertNotNull($expectedOrderShipmentEntity, 'Order shipment could not be found! Order shipment has been saved incorrectly.');
        $this->assertEquals($countExpensesBefore, $salesExpenseQuery->count(), 'Order shipment expenses count mismatch! There is no any shipment expense should be saved.');
    }

    /**
     * @dataProvider shipmentOrderSaverShouldUseMultipleShipmentsWithoutShippingAddressAndMethodDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $expectedCountOfShipments
     *
     * @return void
     */
    public function testShipmentOrderSaverShouldUseMultipleShipmentsWithoutShippingAddressAndMethod(
        QuoteTransfer $quoteTransfer,
        int $countOfNewShipments
    ): void {
        // Arrange
        $savedOrderTransfer = $this->haveOrder($quoteTransfer, 'Test01');

        $salesShipmentQuery = SpySalesShipmentQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countShipmentsBefore = $salesShipmentQuery->count();

        $salesExpenseQuery = SpySalesExpenseQuery::create()->filterByFkSalesOrder($savedOrderTransfer->getIdSalesOrder());
        $countExpensesBefore = $salesExpenseQuery->count();

        $shipmentFacade = $this->tester->getFacade();

        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessageRegExp('/^Missing required property "\w+" for transfer/');

        // Act
        $shipmentFacade->saveOrderShipment($quoteTransfer, $savedOrderTransfer);

        // Assert
        $expectedOrderShipmentCount = $countShipmentsBefore + $countOfNewShipments;
        $expectedOrderShipmentEntity = $salesShipmentQuery->findOne();

        $this->assertEquals($expectedOrderShipmentCount, $salesShipmentQuery->count(), 'There is no any shipment should be saved.');
        $this->assertNotNull($expectedOrderShipmentEntity, 'Order shipment should not be saved incorrectly.');
        $this->assertEquals($countExpensesBefore, $salesExpenseQuery->count(), 'There is no any shipment expense should be saved.');
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteShipmentDataProvider(): array
    {
        return [
            'any data; expected: shipment in DB' => $this->getDataWithQuoteLevelShipmentToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteShipmentAndExpenseDataProvider(): array
    {
        return [
            'any data, expense set; expected: shipment and expense in DB' => $this->getDataWithQuoteLevelShipmentToFranceWithExpense(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteShipmentAndShippingAddressDataProvider(): array
    {
        return [
            'any data, 1 address; expected: shipment with shipping address in DB' => $this->getDataWithQuoteLevelShipmentAndShippingAddressToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseQuoteShipmentAndItemsDataProvider(): array
    {
        return [
            'any data, 2 items; expected: shipment connected to order items in DB' => $this->getDataWithQuoteLevelShipmentAnd2ItemsToFrance(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersistedDataProvider(): array
    {
        return [
            'France 1 item; expected: 1 order shipment in DB' => $this->getDataWithMultipleShipmentsAnd1ItemToFrance(),
            'France 2 items, Germany 1 item; expected: 2 order shipments in DB' => $this->getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermany(),
            'France 3 items; expected: 1 order shipments in DB' => $this->getDataWithMultipleShipmentsAnd3ItemsToFrance(),
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
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentAddressesDataProvider(): array
    {
        return [
            'France 1 item, 1 address; expected: 1 order shipment with shipping address in DB' => $this->getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(),
            'France 2 items, Germany 1 item, 2 addresses; expected: 2 order shipments with shipping addresses in DB' => $this->getDataWithMultipleShipmentsAndShippingAddressesAnd2ItemsToFranceAnd1ItemToGermany(),
        ];
    }

    /**
     * @todo Duplicate for shipmentOrderSaverShouldUseMultipleShipmentsWithMultipleShipmentsArePersistedDataProvider?
     *
     * @return array
     */
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithItemsDataProvider(): array
    {
        return [
            'France 1 item; expected: 1 order shipment connected to order item in DB' => $this->getDataWithMultipleShipmentsAnd1ItemToFrance(),
            'France 2 items, Germany 1 item; expected: 2 order shipments connected to order items in DB' => $this->getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermany(),
        ];
    }

    /**
     * @return array
     */
    public function shipmentOrderSaverShouldUseMultipleShipmentsWithoutShippingAddressAndMethodDataProvider(): array
    {
        return [
            '1 item, no shipping address; expected: exception' => $this->getDataWithMultipleShipmentsAnd1ItemWithoutShippingAddress(),
            '1 item, no shipping method; expected: exception' => $this->getDataWithMultipleShipmentsAnd1ItemWithoutShippingMethod(),
            '1 item, no shipping address and method; expected: exception' => $this->getDataWithMultipleShipmentsAnd1ItemWithoutShippingAddressAndMethod(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithQuoteLevelShipmentToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherItem()
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
    protected function getDataWithQuoteLevelShipmentAndShippingAddressToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherItem()
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
    protected function getDataWithQuoteLevelShipmentAnd2ItemsToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->withAnotherItem()
            ->withAnotherItem()
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
    protected function getDataWithMultipleShipmentsAnd1ItemToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $itemBuilder = (new ItemBuilder())
            ->withAnotherShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherItem($itemBuilder)
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, 1];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd2ItemsToFranceAnd1ItemToGermany(): array
    {
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod()
            ->build();
        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder2)
            ->withAnotherMethod()
            ->build();
        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer2);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);
        $quoteTransfer->addItem($itemTransfer3);

        return [$quoteTransfer, 2];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd3ItemsToFrance(): array
    {
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod()
            ->build();

        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer1);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);
        $quoteTransfer->addItem($itemTransfer3);

        return [$quoteTransfer, 1];
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

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder)
            ->withAnotherMethod();

        $itemBuilder = (new ItemBuilder())
            ->withAnotherShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherItem($itemBuilder)
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer, 1];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd2ItemsToFranceAnd1ItemToGermany(): array
    {
        $addressBuilder1 = (new AddressBuilder(['iso2Code' => 'FR']));
        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder1)
            ->withAnotherMethod()
            ->build();
        $itemTransfer1 = (new ItemBuilder())->build();
        $itemTransfer1->setShipment($shipmentTransfer1);

        $itemTransfer2 = (new ItemBuilder())->build();
        $itemTransfer2->setShipment($shipmentTransfer1);

        $addressBuilder2 = (new AddressBuilder(['iso2Code' => 'DE']));
        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder2)
            ->withAnotherMethod()
            ->build();
        $itemTransfer3 = (new ItemBuilder())->build();
        $itemTransfer3->setShipment($shipmentTransfer2);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);
        $quoteTransfer->addItem($itemTransfer3);

        return [$quoteTransfer, 2];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd1ItemWithoutShippingAddress(): array
    {
        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherMethod();

        $itemTransfer = (new ItemBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->build();
        $itemTransfer->getShipment()->setShippingAddress(null);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 0];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd1ItemWithoutShippingMethod(): array
    {
        $addressBuilder = (new AddressBuilder(['iso2Code' => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withAnotherShippingAddress($addressBuilder);

        $itemTransfer = (new ItemBuilder())
            ->withAnotherShipment($shipmentBuilder)
            ->build();
        $itemTransfer->getShipment()->setMethod(null);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 0];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAnd1ItemWithoutShippingAddressAndMethod(): array
    {
        $itemTransfer = (new ItemBuilder())
            ->withAnotherShipment()
            ->build();
        $itemTransfer->getShipment()->setMethod(null);
        $itemTransfer->getShipment()->setShippingAddress(null);

        $quoteTransfer = (new QuoteBuilder())
            ->withAnotherBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer->addItem($itemTransfer);

        return [$quoteTransfer, 0];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $testStateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function haveOrder(QuoteTransfer $quoteTransfer, string $testStateMachineProcessName): SaveOrderTransfer
    {
        $testStateMachineProcessName = 'Test01';
        $this->tester->configureTestStateMachine([$testStateMachineProcessName]);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productTransfer = $this->tester->haveProduct($itemTransfer->toArray());
        }
        $savedOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, $testStateMachineProcessName);

        return $savedOrderTransfer;
    }
}