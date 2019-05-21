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
use Spryker\Zed\Availability\Communication\Plugin\ProductsAvailableCheckoutPreConditionPlugin;
use Spryker\Zed\Checkout\CheckoutConfig;
use Spryker\Zed\Checkout\CheckoutDependencyProvider;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Communication\Plugin\CustomerPreConditionCheckerPlugin;
use Spryker\Zed\Customer\Communication\Plugin\OrderCustomerSavePlugin;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Communication\Plugin\SalesOrderSaverPlugin;
use SprykerTest\Shared\Sales\Helper\Config\TesterSalesConfig;

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

        $this->tester->setDependency(CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS, [
            new CustomerPreConditionCheckerPlugin(),
            new ProductsAvailableCheckoutPreConditionPlugin(),
        ]);

        $this->tester->setDependency(CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS, [
            $this->createSalesOrderSaverPlugin(),
            $this->createCustomerOrderSavePlugin(),
        ]);
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

        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

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

        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

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

        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

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

        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

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

        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

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

        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

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

        $this->tester->getFacade()->placeOrder($quoteTransfer);

        $orderItem1Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337');

        $orderItem2Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338');

        $omsConfig = new OmsConfig();

        $orderItem1 = $orderItem1Query->findOne();
        $orderItem2 = $orderItem2Query->findOne();

        $this->assertNotNull($orderItem1);
        $this->assertNotNull($orderItem2);

        $this->assertEquals($omsConfig->getInitialStatus(), $orderItem1->getState()->getName());
        $this->assertEquals($omsConfig->getInitialStatus(), $orderItem2->getState()->getName());
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
            ->setSumGrossPrice(3000)
            ->setName('Product1');

        $item2 = new ItemTransfer();
        $item2
            ->setUnitPrice(4000)
            ->setSku('OSB1338')
            ->setQuantity(1)
            ->setUnitGrossPrice(4000)
            ->setSumGrossPrice(4000)
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
     * @return \Spryker\Zed\Sales\Communication\Plugin\SalesOrderSaverPlugin
     */
    protected function createSalesOrderSaverPlugin()
    {
        $salesOrderSaverPlugin = new SalesOrderSaverPlugin();
        $salesOrderSaverPlugin->setFacade($this->createSalesFacadeMock());

        return $salesOrderSaverPlugin;
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
     * @param string $testStateMachineProcessName
     *
     * @return \Spryker\Zed\Sales\Business\SalesFacade
     */
    protected function createSalesFacadeMock($testStateMachineProcessName = 'Test01')
    {
        $this->tester->configureTestStateMachine([$testStateMachineProcessName]);

        $salesConfig = new TesterSalesConfig();
        $salesConfig->setStateMachineProcessName($testStateMachineProcessName);

        $salesBusinessFactory = new SalesBusinessFactory();
        $salesBusinessFactory->setConfig($salesConfig);

        $salesFacade = new SalesFacade();
        $salesFacade->setFactory($salesBusinessFactory);

        return $salesFacade;
    }
}
