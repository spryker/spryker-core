<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Price\PriceMode;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SalesFacadeSaveOrderTest
 * Add your own group annotations below this line
 */
class SalesFacadeSaveOrderTest extends Unit
{
    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacade
     */
    protected $salesFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $countryFacadeMock = $this->getMockBuilder(SalesToCountryInterface::class)->setMethods(['getIdCountryByIso2Code', 'getAvailableCountries'])->getMock();
        $countryFacadeMock->method('getIdCountryByIso2Code')
            ->will($this->returnValue(1));

        $omsOrderProcessEntity = $this->getProcessEntity();

        $omsFacadeMock = $this->getMockBuilder(SalesToOmsInterface::class)
            ->setMethods([
                'selectProcess',
                'getInitialStateEntity',
                'getProcessEntity',
                'getManualEvents',
                'getItemsWithFlag',
                'getManualEventsByIdSalesOrder',
                'getDistinctManualEventsByIdSalesOrder',
                'getOrderItemMatrix',
                'isOrderFlaggedExcludeFromCustomer',
            ])
            ->getMock();
        $omsFacadeMock->method('selectProcess')
            ->will($this->returnValue('CheckoutTest01'));

        $omcConfig = new OmsConfig();

        $initialStateEntity = SpyOmsOrderItemStateQuery::create()
            ->filterByName($omcConfig->getInitialStatus())
            ->findOneOrCreate();
        $initialStateEntity->save();

        $omsFacadeMock->method('getInitialStateEntity')
            ->will($this->returnValue($initialStateEntity));

        $omsFacadeMock->method('getProcessEntity')
            ->will($this->returnValue($omsOrderProcessEntity));

        $sequenceNumberFacade = new SequenceNumberFacade();

        $container = new Container();
        $container[SalesDependencyProvider::FACADE_COUNTRY] = new SalesToCountryBridge($countryFacadeMock);
        $container[SalesDependencyProvider::FACADE_OMS] = new SalesToOmsBridge($omsFacadeMock);
        $container[SalesDependencyProvider::FACADE_SEQUENCE_NUMBER] = new SalesToSequenceNumberBridge($sequenceNumberFacade);
        $container[SalesDependencyProvider::QUERY_CONTAINER_LOCALE] = new LocaleQueryContainer();
        $container[SalesDependencyProvider::STORE] = Store::getInstance();
        $container[SalesDependencyProvider::ORDER_EXPANDER_PRE_SAVE_PLUGINS] = [];
        $container[SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS] = function (Container $container) {
            return [];
        };

        $this->salesFacade = new SalesFacade();
        $businessFactory = new SalesBusinessFactory();
        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)->setMethods(['determineProcessForOrderItem'])->getMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn('');
        $businessFactory->setConfig($salesConfigMock);
        $businessFactory->setContainer($container);
        $this->salesFacade->setFactory($businessFactory);
        $country = new SpyCountry();
        $country->setIso2Code('ix');
        $country->save();
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesBillingAddressAndAssignsItToOrder()
    {
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()
            ->filterByAddress1('address-1-1-test')
            ->filterByFirstName('Max')
            ->filterByLastName('Mustermann')
            ->filterByZipCode('1337')
            ->filterByCity('SpryHome');

        $quoteTransfer = $this->getValidBaseQuoteTransfer();

        $this->salesFacade->saveOrder($quoteTransfer, $this->getValidBaseResponseTransfer());

        $addressEntity = $salesOrderAddressQuery->findOne();

        $this->assertNotNull($addressEntity);
        $this->assertSame($addressEntity->getIdSalesOrderAddress(), $quoteTransfer->getBillingAddress()
            ->getIdSalesOrderAddress());
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    private function getValidBaseResponseTransfer()
    {
        return (new CheckoutResponseTransfer())
            ->setSaveOrder(new SaveOrderTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    private function createSaveOrderTransfer()
    {
        return new SaveOrderTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function getValidBaseQuoteTransfer()
    {
        $quoteTransfer = $this->createValidQuoteWithoutItems();
        $quoteTransfer->addItem($this->createItemWithIntQuantity());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createValidBaseQuoteWithFloatQuantity(): QuoteTransfer
    {
        $quoteTransfer = $this->createValidQuoteWithoutItems();
        $quoteTransfer->addItem($this->createItemWithFloatQuantity());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemWithIntQuantity(): ItemTransfer
    {
        return (new ItemTransfer())
            ->setUnitPrice(1)
            ->setUnitGrossPrice(1)
            ->setSumGrossPrice(1)
            ->setQuantity(1)
            ->setName('test-name')
            ->setSku('sku-test');
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemWithFloatQuantity(): ItemTransfer
    {
        return (new ItemTransfer())
            ->setUnitPrice(1)
            ->setUnitGrossPrice(1)
            ->setSumGrossPrice(1)
            ->setQuantity(1.5)
            ->setName('test-name-float')
            ->setSku('sku-test-float');
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createValidQuoteWithoutItems(): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $quoteTransfer->setCurrency($currencyTransfer);

        $quoteTransfer->setPriceMode(PriceMode::PRICE_MODE_GROSS);
        $billingAddress = new AddressTransfer();

        $billingAddress->setIso2Code('ix')
            ->setAddress1('address-1-1-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $shippingAddress = new AddressTransfer();
        $shippingAddress->setIso2Code('ix')
            ->setAddress1('address-1-2-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(1337)
            ->setSubtotal(337);

        $totals->setTaxTotal((new TaxTotalTransfer())->setAmount(10));

        $quoteTransfer->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setTotals($totals);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail('max@mustermann.de');
        $customerTransfer->setFirstName('Max');
        $customerTransfer->setLastName('Mustermann');

        $quoteTransfer->setCustomer($customerTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setMethod(new ShipmentMethodTransfer());
        $quoteTransfer->setShipment($shipmentTransfer);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection('dummyPaymentInvoice');

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesShippingAddressAndAssignsItToOrder()
    {
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()
            ->filterByAddress1('address-1-2-test')
            ->filterByFirstName('Max')
            ->filterByLastName('Mustermann')
            ->filterByCity('SpryHome');

        $quoteTransfer = $this->getValidBaseQuoteTransfer();

        $this->salesFacade->saveOrder($quoteTransfer, $this->getValidBaseResponseTransfer());

        $addressEntity = $salesOrderAddressQuery->findOne();

        $this->assertNotNull($addressEntity);
        $this->assertSame($addressEntity->getIdSalesOrderAddress(), $quoteTransfer->getShippingAddress()
            ->getIdSalesOrderAddress());
    }

    /**
     * @return void
     */
    public function testSaveOrderAssignsSavedOrderId()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        $this->assertNotNull($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesOrderAndSavesFieldsDeprecated()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        $orderQuery = SpySalesOrderQuery::create()
            ->filterByPrimaryKey($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder());

        $orderEntity = $orderQuery->findOne();
        $this->assertNotNull($orderEntity);

        $this->assertSame('max@mustermann.de', $orderEntity->getEmail());
        $this->assertSame('Max', $orderEntity->getFirstName());
        $this->assertSame('Mustermann', $orderEntity->getLastName());
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesOrderAndSavesFields()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();
        $saveOrderTransfer = $this->createSaveOrderTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);

        $orderQuery = SpySalesOrderQuery::create()
            ->filterByPrimaryKey($saveOrderTransfer->getIdSalesOrder());

        $orderEntity = $orderQuery->findOne();
        $this->assertNotNull($orderEntity);

        $this->assertSame('max@mustermann.de', $orderEntity->getEmail());
        $this->assertSame('Max', $orderEntity->getFirstName());
        $this->assertSame('Mustermann', $orderEntity->getLastName());
    }

    /**
     * @return void
     */
    public function testSaveOrderWhenCustomerHaveCreatedAtSetShouldNotOverwriteOrderData()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();
        $customerCreatedAt = new DateTime('Yesterday');
        $quoteTransfer->getCustomer()->setCreatedAt($customerCreatedAt);

        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        $orderQuery = SpySalesOrderQuery::create()
            ->filterByPrimaryKey($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder());

        $orderEntity = $orderQuery->findOne();
        $this->assertNotNull($orderEntity);

        $this->assertNotEquals($customerCreatedAt->format('Y-m-d'), $orderEntity->getCreatedAt('Y-m-d'), 'Dates are not expected to be equal.');
    }

    /**
     * @dataProvider saveOrderCreatesAndFillsOrderItemsProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $firstItem
     * @param \Generated\Shared\Transfer\ItemTransfer $secondItem
     *
     * @return void
     */
    public function testSaveOrderCreatesAndFillsOrderItems(QuoteTransfer $quoteTransfer, ItemTransfer $firstItem, ItemTransfer $secondItem)
    {
        $omsConfig = new OmsConfig();

        $initialState = SpyOmsOrderItemStateQuery::create()
            ->filterByName($omsConfig->getInitialStatus())
            ->findOneOrCreate();
        $initialState->save();

        $this->assertNotNull($initialState->getIdOmsOrderItemState());

        $quoteTransfer->addItem($firstItem);
        $quoteTransfer->addItem($secondItem);

        $item1Query = SpySalesOrderItemQuery::create()
            ->filterByName('item-test-1');

        $item2Query = SpySalesOrderItemQuery::create()
            ->filterByName('item-test-2');

        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        $savedItems = $checkoutResponseTransfer->getSaveOrder()->getOrderItems();

        $item1Entity = $item1Query->findOne();
        $item2Entity = $item2Query->findOne();

        $this->assertNotNull($item1Entity);
        $this->assertNotNull($item2Entity);

        $this->assertSame($savedItems[1]->getIdSalesOrderItem(), $item1Entity->getIdSalesOrderItem());
        $this->assertSame($firstItem->getName(), $item1Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item1Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item1Entity->getFkOmsOrderItemState());
        $this->assertSame($firstItem->getSku(), $item1Entity->getSku());
        $this->assertSame($savedItems[1]->getUnitGrossPrice(), $item1Entity->getGrossPrice());
        $this->assertSame($firstItem->getQuantity(), $item1Entity->getQuantity());

        $this->assertSame($savedItems[2]->getIdSalesOrderItem(), $item2Entity->getIdSalesOrderItem());
        $this->assertSame($secondItem->getName(), $item2Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item2Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item2Entity->getFkOmsOrderItemState());
        $this->assertSame($secondItem->getSku(), $item2Entity->getSku());
        $this->assertSame($savedItems[2]->getUnitGrossPrice(), $item2Entity->getGrossPrice());
        $this->assertSame($secondItem->getQuantity(), $item2Entity->getQuantity());
    }

    /**
     * @return array
     */
    public function saveOrderCreatesAndFillsOrderItemsProvider(): array
    {
        return [
            'int stock' => $this->saveOrderCreatesAndFillsOrderItemsIntStockData(),
            'float stock' => $this->saveOrderCreatesAndFillsOrderItemsFloatStockData(),
        ];
    }

    /**
     * @return array
     */
    protected function saveOrderCreatesAndFillsOrderItemsIntStockData(): array
    {
        $firstItem = new ItemTransfer();
        $firstItem->setName('item-test-1')
            ->setSku('sku1')
            ->setUnitPrice(130)
            ->setUnitGrossPrice(120)
            ->setSumGrossPrice(120)
            ->setQuantity(1.0)
            ->setTaxRate(19);

        $secondItem = new ItemTransfer();
        $secondItem->setName('item-test-2')
            ->setSku('sku2')
            ->setUnitPrice(130)
            ->setUnitGrossPrice(130)
            ->setSumGrossPrice(130)
            ->setQuantity(1.0)
            ->setTaxRate(19);

        return [
            $this->getValidBaseQuoteTransfer(),
            $firstItem,
            $secondItem,
        ];
    }

    /**
     * @return array
     */
    protected function saveOrderCreatesAndFillsOrderItemsFloatStockData(): array
    {
        $firstItem = new ItemTransfer();
        $firstItem->setName('item-test-1')
            ->setSku('sku1')
            ->setUnitPrice(130)
            ->setUnitGrossPrice(120)
            ->setSumGrossPrice(120)
            ->setQuantity(1.5)
            ->setTaxRate(19);

        $secondItem = new ItemTransfer();
        $secondItem->setName('item-test-2')
            ->setSku('sku2')
            ->setUnitPrice(130)
            ->setUnitGrossPrice(130)
            ->setSumGrossPrice(130)
            ->setQuantity(2.5)
            ->setTaxRate(19);

        return [
            $this->getValidBaseQuoteTransfer(),
            $firstItem,
            $secondItem,
        ];
    }

    /**
     * @return void
     */
    public function testSaveOrderGeneratesOrderReference()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);
        $this->assertNotNull($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @dataProvider createSalesExpenseSaveExpenseDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    public function testCreateSalesExpenseSavesExpense(QuoteTransfer $quoteTransfer, ExpenseTransfer $expenseTransfer): void
    {
        // Assign
        $saveOrderTransfer = $this->createSaveOrderTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);
        $expenseTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $savedExpenseTransfer = $this->salesFacade->createSalesExpense($expenseTransfer);
        $expenseTransfer->setIdSalesExpense($savedExpenseTransfer->getIdSalesExpense());

        // Assert
        $this->assertNotNull($savedExpenseTransfer->getIdSalesExpense());
        $this->assertEquals($savedExpenseTransfer->toArray(), $expenseTransfer->toArray());
    }

    /**
     * @return array
     */
    public function createSalesExpenseSaveExpenseDataProvider(): array
    {
        return [
            'int stock' => [$this->getValidBaseQuoteTransfer(), $this->createExpenseTransfer()],
            'float stock' => [$this->createValidBaseQuoteWithFloatQuantity(), $this->createExpenseTransferWithFloatQuantity()],
        ];
    }

    /**
     * @param int $expensePrice
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer(int $expensePrice = 100): ExpenseTransfer
    {
        $expenseTransfer = (new ExpenseTransfer())
            ->setName('test expense')
            ->setType('EXPENSE_TYPE')
            ->setUnitPrice($expensePrice)
            ->setSumPrice($expensePrice)
            ->setUnitPriceToPayAggregation($expensePrice)
            ->setSumPriceToPayAggregation($expensePrice)
            ->setTaxRate(19.1)
            ->setQuantity(1)
            ->setUnitGrossPrice(0)
            ->setSumGrossPrice(0)
            ->setUnitNetPrice($expensePrice)
            ->setSumNetPrice($expensePrice);

        return $expenseTransfer;
    }

    /**
     * @param int $expencePrice
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransferWithFloatQuantity(int $expencePrice = 100): ExpenseTransfer
    {
        $expenceTransfer = $this->createExpenseTransfer($expencePrice);
        $expenceTransfer->setQuantity(1.5);

        return $expenceTransfer;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function getProcessEntity()
    {
        $omsOrderProcessEntity = (new SpyOmsOrderProcessQuery())->filterByName('CheckoutTest01')->findOneOrCreate();
        $omsOrderProcessEntity->save();

        return $omsOrderProcessEntity;
    }
}
