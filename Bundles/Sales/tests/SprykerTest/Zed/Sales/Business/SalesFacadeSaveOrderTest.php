<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\TotalsBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Shared\Kernel\Store;
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
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

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
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesBillingAddressAndAssignsItToOrder()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();

        $this->salesFacade->saveOrder($quoteTransfer, $this->getValidBaseResponseTransfer());
        $billingAddress = $quoteTransfer->getBillingAddress();
        $addressEntity = SpySalesOrderAddressQuery::create()
            ->filterByAddress1($billingAddress->getAddress1())
            ->filterByFirstName($billingAddress->getFirstName())
            ->filterByLastName($billingAddress->getLastName())
            ->filterByZipCode($billingAddress->getZipCode())
            ->filterByCity($billingAddress->getCity())
            ->findOne();

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
        $quoteTransfer->addItem($this->createItemWithIntQuantity(1));

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createValidBaseQuoteWithFloatQuantity(): QuoteTransfer
    {
        $quoteTransfer = $this->createValidQuoteWithoutItems();
        $quoteTransfer->addItem($this->createItemWithFloatQuantity(1.5));

        return $quoteTransfer;
    }

    /**
     * @param int $quantity
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemWithIntQuantity(int $quantity, array $seed = []): ItemTransfer
    {
        $seed = array_merge([
            ItemTransfer::QUANTITY => $quantity,
        ], $seed);

        return (new ItemBuilder($seed))->build();
    }

    /**
     * @param float $quantity
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemWithFloatQuantity(float $quantity, array $seed = []): ItemTransfer
    {
        $seed = array_merge([
            ItemTransfer::QUANTITY => $quantity,
        ], $seed);

        return (new ItemBuilder($seed))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createValidQuoteWithoutItems(): QuoteTransfer
    {
        $totalsTransfer = (new TotalsBuilder())->build();
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->setTotals($totalsTransfer);
        $customerTransfer = (new CustomerBuilder())->build();
        $quoteTransfer->setCustomer($customerTransfer);
        $currencyTransfer = (new CurrencyBuilder())->build();
        $quoteTransfer->setCurrency($currencyTransfer);
        $billingAddress = (new AddressBuilder())->build();
        $shippingAddress = (new AddressBuilder())->build();
        $quoteTransfer->setBillingAddress($billingAddress);
        $quoteTransfer->setShippingAddress($shippingAddress);

        return $quoteTransfer;
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesShippingAddressAndAssignsItToOrder()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();

        $this->salesFacade->saveOrder($quoteTransfer, $this->getValidBaseResponseTransfer());
        $shippingAddress = $quoteTransfer->getShippingAddress();

        $addressEntity = SpySalesOrderAddressQuery::create()
            ->filterByAddress1($shippingAddress->getAddress1())
            ->filterByFirstName($shippingAddress->getFirstName())
            ->filterByLastName($shippingAddress->getLastName())
            ->filterByCity($shippingAddress->getCity())
            ->findOne();

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

        $this->assertSame($quoteTransfer->getCustomer()->getEmail(), $orderEntity->getEmail());
        $this->assertSame($quoteTransfer->getCustomer()->getFirstName(), $orderEntity->getFirstName());
        $this->assertSame($quoteTransfer->getCustomer()->getLastName(), $orderEntity->getLastName());
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

        $this->assertSame($quoteTransfer->getCustomer()->getEmail(), $orderEntity->getEmail());
        $this->assertSame($quoteTransfer->getCustomer()->getFirstName(), $orderEntity->getFirstName());
        $this->assertSame($quoteTransfer->getCustomer()->getLastName(), $orderEntity->getLastName());
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
            ->filterByName($firstItem->getName());

        $item2Query = SpySalesOrderItemQuery::create()
            ->filterByName($secondItem->getName());

        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        $savedItems = $checkoutResponseTransfer->getSaveOrder()->getOrderItems();

        $item1Entity = $item1Query->findOne();
        $item2Entity = $item2Query->findOne();

        $this->assertNotNull($item1Entity);
        $this->assertNotNull($item2Entity);

        $this->assertSame($savedItems[0]->getIdSalesOrderItem(), $item1Entity->getIdSalesOrderItem());
        $this->assertSame($firstItem->getName(), $item1Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item1Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item1Entity->getFkOmsOrderItemState());
        $this->assertSame($firstItem->getSku(), $item1Entity->getSku());
        $this->assertSame($savedItems[0]->getUnitGrossPrice(), $item1Entity->getGrossPrice());
        $this->assertSame((float)$firstItem->getQuantity(), $item1Entity->getQuantity());

        $this->assertSame($savedItems[1]->getIdSalesOrderItem(), $item2Entity->getIdSalesOrderItem());
        $this->assertSame($secondItem->getName(), $item2Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item2Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item2Entity->getFkOmsOrderItemState());
        $this->assertSame($secondItem->getSku(), $item2Entity->getSku());
        $this->assertSame($savedItems[1]->getUnitGrossPrice(), $item2Entity->getGrossPrice());
        $this->assertSame((float)$secondItem->getQuantity(), $item2Entity->getQuantity());
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
        return [
            $this->createValidQuoteWithoutItems(),
            $this->createItemWithIntQuantity(1, [
                ItemTransfer::NAME => 'test 1',
                ItemTransfer::UNIT_GROSS_PRICE => 120,
                ItemTransfer::SUM_GROSS_PRICE => 120,
            ]),
            $this->createItemWithIntQuantity(1, [
                ItemTransfer::NAME => 'test 2',
                ItemTransfer::UNIT_GROSS_PRICE => 130,
                ItemTransfer::SUM_GROSS_PRICE => 130,
            ]),
        ];
    }

    /**
     * @return array
     */
    protected function saveOrderCreatesAndFillsOrderItemsFloatStockData(): array
    {
        return [
            $this->createValidQuoteWithoutItems(),
            $this->createItemWithFloatQuantity(1.5, [
                ItemTransfer::NAME => 'test 1',
                ItemTransfer::UNIT_GROSS_PRICE => 120,
                ItemTransfer::SUM_GROSS_PRICE => 120,
            ]),
            $this->createItemWithFloatQuantity(2.5, [
                ItemTransfer::NAME => 'test 2',
                ItemTransfer::UNIT_GROSS_PRICE => 130,
                ItemTransfer::SUM_GROSS_PRICE => 130,
            ]),
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
            'int stock' => [
                $this->getValidBaseQuoteTransfer(),
                $this->createExpenseTransfer(),
            ],
            'float stock' => [
                $this->createValidBaseQuoteWithFloatQuantity(),
                $this->createExpenseTransferWithFloatQuantity(),
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer(): ExpenseTransfer
    {
        return (new ExpenseBuilder())->seed([ExpenseTransfer::QUANTITY => 1])->seed()->build();
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransferWithFloatQuantity(): ExpenseTransfer
    {
        return (new ExpenseBuilder())->seed([ExpenseTransfer::QUANTITY => 1])->seed()->build();
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
