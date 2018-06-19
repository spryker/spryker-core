<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Checkout\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Availability\Communication\Plugin\ProductsAvailableCheckoutPreConditionPlugin;
use Spryker\Zed\Checkout\Business\CheckoutBusinessFactory;
use Spryker\Zed\Checkout\Business\CheckoutFacade;
use Spryker\Zed\Checkout\CheckoutConfig;
use Spryker\Zed\Checkout\CheckoutDependencyProvider;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeBridge;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Communication\Plugin\CustomerPreConditionCheckerPlugin;
use Spryker\Zed\Customer\Communication\Plugin\OrderCustomerSavePlugin;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;
use Spryker\Zed\Oms\Business\OmsBusinessFactory;
use Spryker\Zed\Oms\Business\OmsFacade;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Communication\Plugin\SalesOrderSaverPlugin;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Checkout
 * @group Business
 * @group Facade
 * @group CheckoutFacadeTest
 * Add your own group annotations below this line
 */
class CheckoutFacadeTest extends Unit
{
    protected const CHECKOUT_ERROR_MESSAGE_TRANSFER_VALUE = 'CHECKOUT_ERROR_MESSAGE_TRANSFER_VALUE';
    protected const CHECKOUT_ERROR_MESSAGE_TRANSFER_PARAMETERS = ['testParameter' => 'testValue'];

    /**
     * @var \Spryker\Zed\Checkout\Business\CheckoutFacade
     */
    protected $checkoutFacade;

    /**
     * @var \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \SprykerTest\Zed\Checkout\CheckoutBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->checkoutFacade = new CheckoutFacade();
        $this->messengerFacade = $this->tester->getMessengerFacade();

        $factoryMock = $this->getFactory();
        $this->checkoutFacade->setFactory($factoryMock);
    }

    /**
     * @return void
     */
    public function testCheckoutSuccessfully()
    {
        $product = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $product->getSku()]);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $product->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore([StoreTransfer::NAME => 'DE'])
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        $result = $this->checkoutFacade->placeOrder($quoteTransfer);

        $this->assertTrue($result->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfCustomerAlreadyRegistered()
    {
        $this->tester->haveCustomer([CustomerTransfer::EMAIL => 'max@mustermann.de']);
        $product = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $product->getSku()]);

        $quoteTransfer = (new QuoteBuilder([CustomerTransfer::EMAIL => 'max@mustermann.de']))
            ->withItem([ItemTransfer::SKU => $product->getSku()])
            ->withStore([StoreTransfer::NAME => 'DE'])
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        $result = $this->checkoutFacade->placeOrder($quoteTransfer);

        $this->assertFalse($result->getIsSuccess());
        $this->assertEquals(1, count($result->getErrors()));
        $this->assertEquals(CheckoutConfig::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutCreatesOrderItems()
    {
        $product1 = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $product1->getSku()]);
        $product2 = $this->tester->haveProduct();
        $this->tester->haveProductInStock([StockProductTransfer::SKU => $product2->getSku()]);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $product1->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withAnotherItem([ItemTransfer::SKU => $product2->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore([StoreTransfer::NAME => 'DE'])
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        $result = $this->checkoutFacade->placeOrder($quoteTransfer);

        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $salesFacade = $this->tester->getLocator()->sales()->facade();
        $order = $salesFacade->getOrderByIdSalesOrder($result->getSaveOrder()->getIdSalesOrder());
        $this->assertEquals(2, $order->getItems()->count());
        $this->assertEquals($product1->getSku(), $order->getItems()[0]->getSku());
        $this->assertEquals($product2->getSku(), $order->getItems()[1]->getSku());
    }

    /**
     * @todo move this code to customer checkout connector, registration can only happen if we have
     * already installed customer bundle
     *
     * @return void
     */
    public function testRegistrationIsTriggeredOnNewNonGuestCustomer()
    {
        $quoteTransfer = $this->getBaseQuoteTransfer();

        $result = $this->checkoutFacade->placeOrder($quoteTransfer);

        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $customerQuery = SpyCustomerQuery::create()->filterByEmail($quoteTransfer->getCustomer()->getEmail());
        $this->assertEquals(1, $customerQuery->count());
    }

    /**
     * @todo move this code to customer checkout connector, registration can only happen if we have
     * already installed customer bundle
     *
     * @return void
     */
    public function testRegistrationDoesNotCreateACustomerIfGuest()
    {
        $quoteTransfer = $this->getBaseQuoteTransfer();
        $quoteTransfer->getCustomer()->setIsGuest(true);

        $result = $this->checkoutFacade->placeOrder($quoteTransfer);

        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $customerQuery = SpyCustomerQuery::create()->filterByEmail($quoteTransfer->getCustomer()->getEmail());
        $this->assertEquals(0, $customerQuery->count());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfStockNotSufficient()
    {
        $quoteTransfer = $this->getBaseQuoteTransfer();
        $productAbstract1 = new SpyProductAbstract();
        $productAbstract1
            ->setSku('AOSB1339')
            ->setAttributes('{}');
        $productConcrete1 = new SpyProduct();
        $productConcrete1
            ->setSku('OSB1339')
            ->setAttributes('{}')
            ->setSpyProductAbstract($productAbstract1)
            ->save();

        $stock = new SpyStock();
        $stock
            ->setName('Stock2');

        $stock1 = new SpyStockProduct();
        $stock1
            ->setQuantity(1)
            ->setStock($stock)
            ->setSpyProduct($productConcrete1)
            ->save();

        $item = new ItemTransfer();
        $item
            ->setSku('OSB1339')
            ->setQuantity(2)
            ->setUnitPrice(3000)
            ->setUnitGrossPrice(3000)
            ->setSumGrossPrice(6000);

        $quoteTransfer->addItem($item);

        $result = $this->checkoutFacade->placeOrder($quoteTransfer);

        $this->assertFalse($result->getIsSuccess());
        $this->assertEquals(1, count($result->getErrors()));
        $this->assertEquals(CheckoutConfig::ERROR_CODE_PRODUCT_UNAVAILABLE, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutTriggersStateMachine()
    {
        $quoteTransfer = $this->getBaseQuoteTransfer();

        $this->checkoutFacade->placeOrder($quoteTransfer);

        $orderItem1Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337');

        $orderItem2Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338');

        $omsConfig = new OmsConfig();

        $orderItem1 = $orderItem1Query->findOne();
        $orderItem2 = $orderItem2Query->findOne();

        $this->assertNotNull($orderItem1);
        $this->assertNotNull($orderItem2);

        $this->assertNotEquals($omsConfig->getInitialStatus(), $orderItem1->getState()->getName());
        $this->assertEquals('waiting for payment', $orderItem2->getState()->getName());
    }

    /**
     * @return void
     */
    public function testAddCheckoutErrorMessageAddsCheckoutErrorMessage(): void
    {
        $messageTransfer = $this->tester->getMessageTransfer();

        $this->tester->getCheckoutFacade()
            ->addCheckoutErrorMessage($messageTransfer);

        $this->assertContains($messageTransfer->getValue(), $this->tester->getStoredMessageValues());
    }

    /**
     * @return void
     */
    public function testAddCheckoutErrorMessageDoesNotAddDuplicatedMessages(): void
    {
        $messageTransfer = $this->tester->getMessageTransfer();

        $storedMessageValues = $this->tester->getStoredMessageValues();

        if (!\in_array(static::CHECKOUT_ERROR_MESSAGE_TRANSFER_VALUE, $storedMessageValues)) {
            $this->tester->getCheckoutFacade()
                ->addCheckoutErrorMessage($messageTransfer);

            $this->tester->getCheckoutFacade()
                ->addCheckoutErrorMessage($messageTransfer);

            $this->assertCount(count($storedMessageValues) + 1, $this->tester->getStoredMessageValues());
        }

        $this->tester->getCheckoutFacade()
            ->addCheckoutErrorMessage($messageTransfer);

        $this->assertCount(count($storedMessageValues), $this->tester->getStoredMessageValues());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getBaseQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();

        $quoteTransfer->setStore((new StoreTransfer())->setName('DE'));

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $quoteTransfer->setCurrency($currencyTransfer);

        $country = new SpyCountry();
        $country
            ->setIso2Code('xi')
            ->save();

        $productAbstract1 = new SpyProductAbstract();
        $productAbstract1
            ->setSku('AOSB1337')
            ->setAttributes('{}');
        $productConcrete1 = new SpyProduct();
        $productConcrete1
            ->setSku('OSB1337')
            ->setAttributes('{}')
            ->setSpyProductAbstract($productAbstract1)
            ->save();

        $productAbstract2 = new SpyProductAbstract();
        $productAbstract2
            ->setSku('AOSB1338')
            ->setAttributes('{}');
        $productConcrete2 = new SpyProduct();
        $productConcrete2
            ->setSku('OSB1338')
            ->setSpyProductAbstract($productAbstract2)
            ->setAttributes('{}')
            ->save();

        $stock = (new SpyStockQuery())
            ->filterByName('Warehouse1')
            ->findOneOrCreate();

        $stock->save();

        $stock1 = new SpyStockProduct();
        $stock1
            ->setQuantity(1)
            ->setStock($stock)
            ->setSpyProduct($productConcrete1)
            ->save();

        $stock2 = new SpyStockProduct();
        $stock2
            ->setQuantity(1)
            ->setStock($stock)
            ->setSpyProduct($productConcrete2)
            ->save();

        $item1 = new ItemTransfer();
        $item1
            ->setUnitPrice(4000)
            ->setSku('OSB1337')
            ->setQuantity(1)
            ->setUnitGrossPrice(3000)
            ->setName('Product1');

        $item2 = new ItemTransfer();
        $item2
            ->setUnitPrice(4000)
            ->setSku('OSB1338')
            ->setQuantity(1)
            ->setUnitGrossPrice(4000)
            ->setName('Product2');

        $quoteTransfer->addItem($item1);
        $quoteTransfer->addItem($item2);

        $totals = new TotalsTransfer();
        $totals
            ->setGrandTotal(1000)
            ->setSubtotal(500);

        $quoteTransfer->setTotals($totals);

        $billingAddress = new AddressTransfer();
        $shippingAddress = new AddressTransfer();

        $billingAddress
            ->setIso2Code('xi')
            ->setEmail('max@mustermann.de')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setAddress1('Straße')
            ->setAddress2('82')
            ->setZipCode('12345')
            ->setCity('Entenhausen');
        $shippingAddress
            ->setIso2Code('xi')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setEmail('max@mustermann.de')
            ->setAddress1('Straße')
            ->setAddress2('84')
            ->setZipCode('12346')
            ->setCity('Entenhausen2');

        $quoteTransfer->setBillingAddress($billingAddress);
        $quoteTransfer->setShippingAddress($shippingAddress);

        $customerTransfer = new CustomerTransfer();

        $customerTransfer
            ->setIsGuest(false)
            ->setEmail('max@mustermann.de');

        $quoteTransfer->setCustomer($customerTransfer);

        $shipment = new ShipmentTransfer();
        $shipment->setMethod(new ShipmentMethodTransfer());

        $quoteTransfer->setShipment($shipment);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection('no_payment');
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        $container = new Container();

        $container[CheckoutDependencyProvider::FACADE_OMS] = function (Container $container) {
            return new CheckoutToOmsFacadeBridge(new OmsFacade());
        };

        $container[CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS] = function (Container $container) {
            return [
                new CustomerPreConditionCheckerPlugin(),
                new ProductsAvailableCheckoutPreConditionPlugin(),
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS] = function (Container $container) {
            $salesOrderSaverPlugin = $this->createOrderSaverPlugin();
            $customerOrderSavePlugin = $this->createCustomerOrderSavePlugin();

            return [
                $salesOrderSaverPlugin,
                $customerOrderSavePlugin,
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_POST_HOOKS] = function (Container $container) {
            return [];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS] = function (Container $container) {
            return [];
        };

        $container[CustomerDependencyProvider::QUERY_CONTAINER_LOCALE] = new LocaleQueryContainer();
        $container[CustomerDependencyProvider::STORE] = Store::getInstance();

        return $container;
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Plugin\OrderCustomerSavePlugin
     */
    protected function createCustomerOrderSavePlugin()
    {
        $container = new Container();
        $customerDependencyProvider = new CustomerDependencyProvider();
        $customerDependencyProvider->provideBusinessLayerDependencies($container);
        $container[CustomerDependencyProvider::FACADE_MAIL] = $this->getMockBuilder(CustomerToMailInterface::class)->getMock();

        $customerFactory = new CustomerBusinessFactory();
        $customerFactory->setContainer($container);

        $customerFacade = new CustomerFacade();
        $customerFacade->setFactory($customerFactory);

        $customerOrderSavePlugin = new OrderCustomerSavePlugin();
        $customerOrderSavePlugin->setFacade($customerFacade);

        return $customerOrderSavePlugin;
    }

    /**
     * @return \Spryker\Zed\Checkout\Business\CheckoutBusinessFactory
     */
    protected function getFactory()
    {
        $container = $this->getContainer();

        $factory = new CheckoutBusinessFactory();
        $factory->setContainer($container);

        return $factory;
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Plugin\SalesOrderSaverPlugin
     */
    protected function createOrderSaverPlugin()
    {
        $salesOrderSaverPlugin = new SalesOrderSaverPlugin();
        $salesBusinessFactoryMock = $this->createSalesBusinessFactoryMock();

        $salesFacade = new SalesFacade();
        $salesFacade->setFactory($salesBusinessFactoryMock);

        $salesOrderSaverPlugin->setFacade($salesFacade);

        return $salesOrderSaverPlugin;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesBusinessFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSalesBusinessFactoryMock()
    {
        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)->setMethods(['determineProcessForOrderItem'])->getMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn('Nopayment01');

        $salesBusinessFactoryMock = $this->getMockBuilder(SalesBusinessFactory::class)->setMethods(['getConfig'])->getMock();
        $salesBusinessFactoryMock->method('getConfig')->willReturn($salesConfigMock);

        $container = new Container();
        $container[SalesDependencyProvider::FACADE_COUNTRY] = function (Container $container) {
            return new SalesToCountryBridge($container->getLocator()->country()->facade());
        };
        $container[SalesDependencyProvider::FACADE_OMS] = function (Container $container) {
            $omsFacade = $container->getLocator()->oms()->facade();

            $omsConfigMock = $this->getMockBuilder(OmsConfig::class)->setMethods(['getActiveProcesses'])->getMock();
            $omsConfigMock->method('getActiveProcesses')->willReturn(['Nopayment01']);

            $omsBusinessFactoryMock = $this->getMockBuilder(OmsBusinessFactory::class)->setMethods(['getConfig'])->getMock();
            $omsBusinessFactoryMock->method('getConfig')->willReturn($omsConfigMock);

            $omsFacade->setFactory($omsBusinessFactoryMock);

            return new SalesToOmsBridge($omsFacade);
        };
        $container[SalesDependencyProvider::FACADE_SEQUENCE_NUMBER] = function (Container $container) {
            return new SalesToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        };
        $container[SalesDependencyProvider::QUERY_CONTAINER_LOCALE] = new LocaleQueryContainer();
        $container[SalesDependencyProvider::STORE] = Store::getInstance();
        $container[SalesDependencyProvider::ORDER_EXPANDER_PRE_SAVE_PLUGINS] = [];

        $container[SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS] = function (Container $container) {
            return [];
        };

        $salesBusinessFactoryMock->setContainer($container);

        return $salesBusinessFactoryMock;
    }
}
