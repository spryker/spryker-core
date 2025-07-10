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
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotalsQuery;
use Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorService;
use Spryker\Shared\CheckoutExtension\CheckoutExtensionContextsInterface;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToStoreBridge;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilUuidGeneratorBridge;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;
use Spryker\Zed\Store\Business\StoreFacade;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
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
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected SalesConfig $salesConfig;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesBusinessFactory
     */
    protected SalesBusinessFactory $salesBusinessFactory;

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected $businessFactoryContainer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $countryFacadeMock = $this->getMockBuilder(SalesToCountryInterface::class)->onlyMethods(['getCountryByIso2Code', 'getAvailableCountries'])->getMock();
        $countryFacadeMock->method('getCountryByIso2Code')
            ->willReturn((new CountryTransfer())->setIdCountry(1));

        $omsOrderProcessEntity = $this->getProcessEntity();

        $omsFacadeMock = $this->getMockBuilder(SalesToOmsInterface::class)
            ->addMethods([
                'selectProcess',
            ])
            ->onlyMethods([
                'getInitialStateEntity',
                'getProcessEntity',
                'getManualEvents',
                'getItemsWithFlag',
                'getManualEventsByIdSalesOrder',
                'getDistinctManualEventsByIdSalesOrder',
                'getGroupedDistinctManualEventsByIdSalesOrder',
                'getOrderItemMatrix',
                'isOrderFlaggedExcludeFromCustomer',
                'triggerEventForOrderItems',
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
        $utilUuidGeneratorService = new UtilUuidGeneratorService();

        $this->businessFactoryContainer = new Container();
        $this->businessFactoryContainer[SalesDependencyProvider::FACADE_COUNTRY] = new SalesToCountryBridge($countryFacadeMock);
        $this->businessFactoryContainer[SalesDependencyProvider::FACADE_OMS] = new SalesToOmsBridge($omsFacadeMock);
        $this->businessFactoryContainer[SalesDependencyProvider::FACADE_SEQUENCE_NUMBER] = new SalesToSequenceNumberBridge($sequenceNumberFacade);
        $this->businessFactoryContainer[SalesDependencyProvider::SERVICE_UTIL_UUID_GENERATOR] = new SalesToUtilUuidGeneratorBridge($utilUuidGeneratorService);
        $this->businessFactoryContainer[SalesDependencyProvider::PROPEL_QUERY_LOCALE] = new SpyLocaleQuery();
        $this->businessFactoryContainer[SalesDependencyProvider::FACADE_STORE] = new SalesToStoreBridge(new StoreFacade());
        $this->businessFactoryContainer[SalesDependencyProvider::FACADE_LOCALE] = new SalesToLocaleBridge(new LocaleFacade());
        $this->businessFactoryContainer[SalesDependencyProvider::ORDER_EXPANDER_PRE_SAVE_PLUGINS] = [];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE] = [];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT] = [];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT_ASYNC] = [];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_ITEM_EXPANDER] = [];
        $this->businessFactoryContainer[SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS] = function (Container $container) {
            return [];
        };
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_ITEMS_POST_SAVE] = function () {
            return [];
        };
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER] = [];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER_FOR_ORDER_AMENDMENT] = [];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_ITEM_INITIAL_STATE_PROVIDER_FOR_ORDER_AMENDMENT_ASYNC] = [];

        $this->salesFacade = new SalesFacade();
        $this->salesBusinessFactory = new SalesBusinessFactory();
        $this->salesConfig = $this->getMockBuilder(SalesConfig::class)->onlyMethods(['determineProcessForOrderItem'])->getMock();
        $this->salesConfig->method('determineProcessForOrderItem')->willReturn('');
        $this->salesBusinessFactory->setConfig($this->salesConfig);
        $this->salesBusinessFactory->setContainer($this->businessFactoryContainer);
        $this->salesFacade->setFactory($this->salesBusinessFactory);
    }

    /**
     * @dataProvider saveSalesOrderSelectsContextCorrectlyDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteProcessFlowTransfer|null $quoteProcessFlowTransfer
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface $checkoutContextPluginMock
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface $orderAmendmentContextPluginMock
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface $orderAmendmentAsyncContextPluginMock
     *
     * @return void
     */
    public function testSaveSalesOrderSelectsContextCorrectly(
        $quoteProcessFlowTransfer,
        $checkoutContextPluginMock,
        $orderAmendmentContextPluginMock,
        $orderAmendmentAsyncContextPluginMock
    ): void {
        // Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $quoteTransfer->setQuoteProcessFlow($quoteProcessFlowTransfer);
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();

        // Assert
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE] = [$checkoutContextPluginMock];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT] = [$orderAmendmentContextPluginMock];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT_ASYNC] = [$orderAmendmentAsyncContextPluginMock];

        // Act
        $this->salesFacade->saveSalesOrder($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface
     */
    protected function getNeverCalledOrderPostSavePluginMock(): OrderPostSavePluginInterface
    {
        $orderPostSavePluginMock = $this
            ->getMockBuilder(OrderPostSavePluginInterface::class)
            ->getMock();
        $orderPostSavePluginMock->expects($this->never())->method('execute');

        return $orderPostSavePluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface
     */
    protected function getOnceCalledOrderPostSavePluginMock(): OrderPostSavePluginInterface
    {
        $orderPostSavePluginMock = $this
            ->getMockBuilder(OrderPostSavePluginInterface::class)
            ->getMock();
        $orderPostSavePluginMock->expects($this->once())->method('execute');

        return $orderPostSavePluginMock;
    }

    /**
     * @return array<array>
     */
    protected function saveSalesOrderSelectsContextCorrectlyDataProvider(): array
    {
        return [
            'Calls default context when default context is set' => [
                (new QuoteProcessFlowTransfer())->setName(CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT),
                $this->getOnceCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
            ],
            'Calls default context when quote process flow is not set' => [
                null,
                $this->getOnceCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
            ],
            'Calls default context when context is not defined' => [
                (new QuoteProcessFlowTransfer())->setName('wrong-context'),
                $this->getOnceCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
            ],
            'Calls order amendment context when order amendment context is set' => [
                (new QuoteProcessFlowTransfer())->setName(SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT),
                $this->getNeverCalledOrderPostSavePluginMock(),
                $this->getOnceCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
            ],
            'Calls order amendment context when order amendment async context is set' => [
                (new QuoteProcessFlowTransfer())->setName(SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT_ASYNC),
                $this->getNeverCalledOrderPostSavePluginMock(),
                $this->getNeverCalledOrderPostSavePluginMock(),
                $this->getOnceCalledOrderPostSavePluginMock(),
            ],
        ];
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesBillingAddressAndAssignsItToOrder(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $addressTransfer = $quoteTransfer->getBillingAddress();

        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()
            ->filterByAddress1($addressTransfer->getAddress1())
            ->filterByFirstName($addressTransfer->getFirstName())
            ->filterByLastName($addressTransfer->getLastName())
            ->filterByZipCode($addressTransfer->getZipCode())
            ->filterByCity($addressTransfer->getCity());

        $this->salesFacade->saveSalesOrder($quoteTransfer, $this->getValidBaseResponseTransfer()->getSaveOrder());

        // Act
        $addressEntity = $salesOrderAddressQuery->findOne();

        // Assert
        $this->assertNotNull($addressEntity);
        $this->assertSame($addressEntity->getIdSalesOrderAddress(), $quoteTransfer->getBillingAddress()
            ->getIdSalesOrderAddress());
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    private function getValidBaseResponseTransfer(): CheckoutResponseTransfer
    {
        return (new CheckoutResponseTransfer())
            ->setSaveOrder(new SaveOrderTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    private function createSaveOrderTransfer(): SaveOrderTransfer
    {
        return new SaveOrderTransfer();
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesShippingAddressAndAssignsItToOrder(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $addressTransfer = $quoteTransfer->getShippingAddress();

        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()
            ->filterByAddress1($addressTransfer->getAddress1())
            ->filterByFirstName($addressTransfer->getFirstName())
            ->filterByLastName($addressTransfer->getLastName())
            ->filterByZipCode($addressTransfer->getZipCode())
            ->filterByCity($addressTransfer->getCity());

        $this->salesFacade->saveSalesOrder($quoteTransfer, $this->getValidBaseResponseTransfer()->getSaveOrder());

        // Act
        $addressEntity = $salesOrderAddressQuery->findOne();

        // Assert
        $this->assertNotNull($addressEntity);
        $this->assertSame($addressEntity->getIdSalesOrderAddress(), $quoteTransfer->getShippingAddress()
            ->getIdSalesOrderAddress());
    }

    /**
     * @return void
     */
    public function testSaveOrderAssignsSavedOrderId(): void
    {
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());

        $this->assertNotNull($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesOrderAndSavesFieldsDeprecated(): void
    {
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());

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
    public function testSaveOrderCreatesOrderAndSavesFields(): void
    {
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
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
    public function testSaveOrderWhenCustomerHaveCreatedAtSetShouldNotOverwriteOrderData(): void
    {
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();

        $customerCreatedAt = new DateTime('Yesterday');
        $quoteTransfer->getCustomer()->setCreatedAt($customerCreatedAt);

        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());

        $orderQuery = SpySalesOrderQuery::create()
            ->filterByPrimaryKey($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder());

        $orderEntity = $orderQuery->findOne();
        $this->assertNotNull($orderEntity);

        $this->assertNotEquals($customerCreatedAt->format('Y-m-d'), $orderEntity->getCreatedAt('Y-m-d'), 'Dates are not expected to be equal.');
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesAndFillsOrderItems(): void
    {
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $initialState = $this->tester->createInitialState();

        $this->assertNotNull($initialState->getIdOmsOrderItemState());

        $item1 = new ItemTransfer();
        $item1->setName('item-test-1')
            ->setSku('sku1')
            ->setUnitPrice(130)
            ->setUnitGrossPrice(120)
            ->setSumGrossPrice(120)
            ->setQuantity(1)
            ->setTaxRate(19);

        $item2 = new ItemTransfer();
        $item2->setName('item-test-2')
            ->setSku('sku2')
            ->setUnitPrice(130)
            ->setUnitGrossPrice(130)
            ->setSumGrossPrice(130)
            ->setQuantity(1)
            ->setTaxRate(19);

        $quoteTransfer->addItem($item1);
        $quoteTransfer->addItem($item2);

        $item1Query = SpySalesOrderItemQuery::create()
            ->filterByName('item-test-1');

        $item2Query = SpySalesOrderItemQuery::create()
            ->filterByName('item-test-2');

        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());

        $savedItems = $checkoutResponseTransfer->getSaveOrder()->getOrderItems();

        $item1Entity = $item1Query->findOne();
        $item2Entity = $item2Query->findOne();

        $this->assertNotNull($item1Entity);
        $this->assertNotNull($item2Entity);

        $this->assertSame($savedItems[1]->getIdSalesOrderItem(), $item1Entity->getIdSalesOrderItem());
        $this->assertSame($item1->getName(), $item1Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item1Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item1Entity->getFkOmsOrderItemState());
        $this->assertSame($item1->getSku(), $item1Entity->getSku());
        $this->assertSame($savedItems[1]->getUnitGrossPrice(), $item1Entity->getGrossPrice());
        $this->assertSame(1, $item1Entity->getQuantity());

        $this->assertSame($savedItems[2]->getIdSalesOrderItem(), $item2Entity->getIdSalesOrderItem());
        $this->assertSame($item2->getName(), $item2Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item2Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item2Entity->getFkOmsOrderItemState());
        $this->assertSame($item2->getSku(), $item2Entity->getSku());
        $this->assertSame($savedItems[2]->getUnitGrossPrice(), $item2Entity->getGrossPrice());
        $this->assertSame(1, $item2Entity->getQuantity());
    }

    /**
     * @return void
     */
    public function testSaveOrderGeneratesOrderReference(): void
    {
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, $checkoutResponseTransfer->getSaveOrder());
        $this->assertNotNull($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @return void
     */
    public function testCreateSalesExpenseSavesExpense(): void
    {
        // Assign
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $saveOrderTransfer = $this->createSaveOrderTransfer();
        $this->salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);
        $expenseTransfer = $this->createExpenseTransfer();
        $expenseTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        // Act
        $savedExpenseTransfer = $this->salesFacade->createSalesExpense($expenseTransfer);
        $expenseTransfer->setIdSalesExpense($savedExpenseTransfer->getIdSalesExpense());

        // Assert
        $this->assertNotNull($savedExpenseTransfer->getIdSalesExpense());
        $this->assertEquals($savedExpenseTransfer->toArray(), $expenseTransfer->toArray());
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
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function getProcessEntity(): SpyOmsOrderProcess
    {
        $omsOrderProcessEntity = (new SpyOmsOrderProcessQuery())->filterByName('CheckoutTest01')->findOneOrCreate();
        $omsOrderProcessEntity->save();

        return $omsOrderProcessEntity;
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesOrderRawAndSavesFields(): void
    {
        //Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        //Act
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);
        $orderQuery = SpySalesOrderQuery::create()
            ->filterByPrimaryKey($saveOrderTransfer->getIdSalesOrder());
        $orderEntity = $orderQuery->findOne();

        //Assert
        $this->assertNotNull($orderEntity);
        $this->assertSame($quoteTransfer->getCustomer()->getEmail(), $orderEntity->getEmail());
        $this->assertSame($quoteTransfer->getCustomer()->getFirstName(), $orderEntity->getFirstName());
        $this->assertSame($quoteTransfer->getCustomer()->getLastName(), $orderEntity->getLastName());
    }

    /**
     * @dataProvider saveSalesOrderSelectsContextCorrectlyDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteProcessFlowTransfer|null $quoteProcessFlowTransfer
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface $checkoutContextPluginMock
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface $orderAmendmentContextPluginMock
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface $orderAmendmentAsyncContextPluginMock
     *
     * @return void
     */
    public function testSaveOrderRawSelectsContextCorrectly(
        $quoteProcessFlowTransfer,
        $checkoutContextPluginMock,
        $orderAmendmentContextPluginMock,
        $orderAmendmentAsyncContextPluginMock
    ): void {
        //Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $quoteTransfer->setQuoteProcessFlow($quoteProcessFlowTransfer);
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        //Assert
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE] = [$checkoutContextPluginMock];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT] = [$orderAmendmentContextPluginMock];
        $this->businessFactoryContainer[SalesDependencyProvider::PLUGINS_ORDER_POST_SAVE_FOR_ORDER_AMENDMENT_ASYNC] = [$orderAmendmentAsyncContextPluginMock];

        //Act
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @return void
     */
    public function testSaveOrderRawCreatesAndFillsOrderItems(): void
    {
        //Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $quoteTransfer = $this->getValidItemsQuoteTransfer($quoteTransfer);
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $saveOrderTransfer = $checkoutResponseTransfer->getSaveOrder();

        //Act
        $initialState = $this->tester->createInitialState();

        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);
        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);

        $savedItems = $saveOrderTransfer->getOrderItems();

        $item1Entity = $this->findSalesOrderItemByName('item-test-1');
        $item2Entity = $this->findSalesOrderItemByName('item-test-2');

        //Assert
        $this->assertNotNull($initialState->getIdOmsOrderItemState());

        $this->assertNotNull($item1Entity);
        $this->assertNotNull($item2Entity);

        $this->assertSame($savedItems[1]->getIdSalesOrderItem(), $item1Entity->getIdSalesOrderItem());
        $this->assertSame($quoteTransfer->getItems()[1]->getName(), $item1Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item1Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item1Entity->getFkOmsOrderItemState());
        $this->assertSame($quoteTransfer->getItems()[1]->getSku(), $item1Entity->getSku());
        $this->assertSame($savedItems[1]->getUnitGrossPrice(), $item1Entity->getGrossPrice());
        $this->assertSame(1, $item1Entity->getQuantity());

        $this->assertSame($savedItems[2]->getIdSalesOrderItem(), $item2Entity->getIdSalesOrderItem());
        $this->assertSame($quoteTransfer->getItems()[2]->getName(), $item2Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item2Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item2Entity->getFkOmsOrderItemState());
        $this->assertSame($quoteTransfer->getItems()[2]->getSku(), $item2Entity->getSku());
        $this->assertSame($savedItems[2]->getUnitGrossPrice(), $item2Entity->getGrossPrice());
        $this->assertSame(1, $item2Entity->getQuantity());
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesOrderRawAndSavesOrderTotals(): void
    {
        //Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        //Act
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);
        $this->salesFacade->saveSalesOrderTotals($quoteTransfer, $saveOrderTransfer);

        $orderTotalsQuery = SpySalesOrderTotalsQuery::create()
            ->filterByFkSalesOrder($saveOrderTransfer->getIdSalesOrder());

        $orderTotalsEntity = $orderTotalsQuery->findOne();

        //Assert
        $this->assertNotNull($orderTotalsEntity);
        $this->assertSame(1337, $orderTotalsEntity->getGrandTotal());
        $this->assertSame(337, $orderTotalsEntity->getSubtotal());
    }

    /**
     * @return void
     */
    public function testSaveOrderRawWithUniqueRandomIdOrderReferenceGenerator(): void
    {
        //Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $saveOrderTransfer = $this->createSaveOrderTransfer();
        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)
            ->onlyMethods(['useUniqueRandomIdOrderReferenceGenerator'])
            ->getMock();
        $salesConfigMock->method('useUniqueRandomIdOrderReferenceGenerator')
            ->willReturn(true);
        $this->salesBusinessFactory->setConfig($salesConfigMock);

        //Act
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        //Assert
        $this->salesBusinessFactory->setConfig($this->salesConfig);
        $this->tester->assertRegExp('/^\w{2}--\d{6}-\d{6}-\d{4}$/', $saveOrderTransfer->getOrderReference());
    }

    /**
     * @return void
     */
    public function testSaveOrderRawUsesSequenceGeneratorByDefault(): void
    {
        //Arrange
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $saveOrderTransfer = $this->createSaveOrderTransfer();

        //Act
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        //Assert
        $this->tester->assertRegExp('/^\w{2}--\d+$/', $saveOrderTransfer->getOrderReference());
        $this->assertFalse($this->salesConfig->useUniqueRandomIdOrderReferenceGenerator());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function getValidItemsQuoteTransfer(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shippingAddress = new AddressTransfer();
        $shippingAddress->setIso2Code('ix')
            ->setAddress1('address-1-2-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setMethod(new ShipmentMethodTransfer());
        $shipmentTransfer->setShippingAddress($shippingAddress);

        $item1 = new ItemTransfer();
        $item1->setName('item-test-1')
            ->setSku('sku1')
            ->setUnitPrice(130)
            ->setUnitGrossPrice(120)
            ->setSumGrossPrice(120)
            ->setQuantity(1)
            ->setTaxRate(19)
            ->setShipment($shipmentTransfer);

        $item2 = new ItemTransfer();
        $item2->setName('item-test-2')
            ->setSku('sku2')
            ->setUnitPrice(130)
            ->setUnitGrossPrice(130)
            ->setSumGrossPrice(130)
            ->setQuantity(1)
            ->setTaxRate(19)
            ->setShipment($shipmentTransfer);

        $quoteTransfer->addItem($item1);
        $quoteTransfer->addItem($item2);

        return $quoteTransfer;
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    private function findSalesOrderItemByName(string $name)
    {
        $itemQuery = SpySalesOrderItemQuery::create()
            ->filterByName($name);

        return $itemQuery->findOne();
    }
}
