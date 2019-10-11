<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Checkout\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\TotalsBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Availability\Communication\Plugin\ProductsAvailableCheckoutPreConditionPlugin;
use Spryker\Zed\Checkout\CheckoutConfig;
use Spryker\Zed\Checkout\CheckoutDependencyProvider;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Communication\Plugin\Checkout\CustomerOrderSavePlugin;
use Spryker\Zed\Customer\Communication\Plugin\CustomerPreConditionCheckerPlugin;
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
 *
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
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $productTransfer->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore($storeTransfer->toArray())
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
    public function testCheckoutSuccessfullyWithItemLevelShippingAddresses()
    {
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer);
        $email = 'frodo.baggins@gmail.com';
        $itemBuilder = (new ItemBuilder([ItemTransfer::SKU => $productTransfer->getSku(), ItemTransfer::UNIT_PRICE => 1]))->withShipment(
            (new ShipmentBuilder())->withShippingAddress([AddressTransfer::EMAIL => $email])
        );

        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemBuilder)
            ->withStore($storeTransfer->toArray())
            ->withCustomer([CustomerTransfer::EMAIL => $email])
            ->withTotals()
            ->withCurrency()
            ->withBillingAddress([AddressTransfer::EMAIL => $email])
            ->withShippingAddress([AddressTransfer::EMAIL => $email])
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
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer);
        $customer = $this->tester->haveCustomer();

        $quoteTransfer = (new QuoteBuilder([CustomerTransfer::EMAIL => 'max@mustermann.de']))
            ->withItem([ItemTransfer::SKU => $productTransfer->getSku()])
            ->withStore($storeTransfer->toArray())
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withBillingAddress()
            ->withShippingAddress(new AddressBuilder([AddressTransfer::EMAIL => $customer->getEmail()]))
            ->build();

        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        $this->assertFalse($result->getIsSuccess());
        $this->assertEquals(1, count($result->getErrors()));
        $this->assertEquals(CheckoutConfig::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfCustomerAlreadyRegisteredWithItemLevelShippingAddress()
    {
        $this->tester->haveCustomer([CustomerTransfer::EMAIL => 'max@mustermann.de']);
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer);
        $customer = $this->tester->haveCustomer();

        $quoteTransfer = (new QuoteBuilder([CustomerTransfer::EMAIL => 'max@mustermann.de']))
            ->withItem($this->createItemWithShipment([ItemTransfer::SKU => $productTransfer->getSku()], $customer))
            ->withStore($storeTransfer->toArray())
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
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
        $productTransfer1 = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer1->getSku(), $storeTransfer);
        $productTransfer2 = $this->tester->haveProduct();
        $this->tester->haveAvailabilityConcrete($productTransfer2->getSku(), $storeTransfer);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $productTransfer1->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withAnotherItem([ItemTransfer::SKU => $productTransfer2->getSku(), ItemTransfer::UNIT_PRICE => 1])
            ->withStore($storeTransfer->toArray())
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
        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($result->getSaveOrder()->getIdSalesOrder());
        $orderItemsSkuList = $this->getOrderItemsSkuList($orderTransfer);

        $this->assertEquals(2, $orderTransfer->getItems()->count());
        $this->assertArrayHasKey($productTransfer1->getSku(), $orderItemsSkuList);
        $this->assertArrayHasKey($productTransfer2->getSku(), $orderItemsSkuList);
    }

    /**
     * @return void
     */
    public function testCheckoutCreatesOrderItemsWithItemLevelShippingAddresses()
    {
        // Arrange
        $productTransfer1 = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer1->getSku(), $storeTransfer);
        $productTransfer2 = $this->tester->haveProduct();
        $this->tester->haveAvailabilityConcrete($productTransfer2->getSku(), $storeTransfer);
        $customer = $this->tester->haveCustomer();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem($this->createItemWithShipment([ItemTransfer::SKU => $productTransfer1->getSku(), ItemTransfer::UNIT_PRICE => 1], $customer))
            ->withAnotherItem($this->createItemWithShipment([ItemTransfer::SKU => $productTransfer2->getSku(), ItemTransfer::UNIT_PRICE => 1], $customer))
            ->withStore($storeTransfer->toArray())
            ->withCustomer([CustomerTransfer::ID_CUSTOMER => $customer->getIdCustomer()])
            ->withTotals()
            ->withCurrency()
            ->withBillingAddress()
            ->build();

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $salesFacade = $this->tester->getLocator()->sales()->facade();
        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($result->getSaveOrder()->getIdSalesOrder());
        $orderItemsSkuList = $this->getOrderItemsSkuList($orderTransfer);

        $this->assertEquals(2, $orderTransfer->getItems()->count());
        $this->assertArrayHasKey($productTransfer1->getSku(), $orderItemsSkuList);
        $this->assertArrayHasKey($productTransfer2->getSku(), $orderItemsSkuList);
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
     * @return void
     */
    public function testRegistrationIsTriggeredOnNewNonGuestCustomerWithItemLevelShippingAddresses()
    {
        $quoteTransfer = $this->getBaseQuoteTransferWithItemLevelShippingAddresses();

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
    public function testRegistrationDoesNotCreateACustomerIfGuestWithItemLevelShippingAddresses()
    {
        $quoteTransfer = $this->getBaseQuoteTransferWithItemLevelShippingAddresses();
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

        $this->tester->haveAvailabilityConcrete('OSB1339', $quoteTransfer->getStore(), 1);

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
    public function testCheckoutResponseContainsErrorIfStockNotSufficientWithItemLevelShippingAddresses()
    {
        $quoteTransfer = $this->getBaseQuoteTransferWithItemLevelShippingAddresses();
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

        $this->tester->haveAvailabilityConcrete($productConcrete1->getSku(), $quoteTransfer->getStore(), 1);

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

        $omsConfig = new OmsConfig();

        $orderItem1 = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337')
            ->findOne();
        $orderItem2 = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338')
            ->findOne();

        $this->assertNotNull($orderItem1);
        $this->assertNotNull($orderItem2);

        $this->assertEquals($omsConfig->getInitialStatus(), $orderItem1->getState()->getName());
        $this->assertEquals($omsConfig->getInitialStatus(), $orderItem2->getState()->getName());
    }

    /**
     * @return void
     */
    public function testCheckoutTriggersStateMachineWithItemLevelShippingAddresses()
    {
        $quoteTransfer = $this->getBaseQuoteTransferWithItemLevelShippingAddresses();

        $this->tester->getFacade()->placeOrder($quoteTransfer);

        $omsConfig = new OmsConfig();

        $orderItem1 = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337')
            ->findOne();
        $orderItem2 = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338')
            ->findOne();

        $this->assertNotNull($orderItem1);
        $this->assertNotNull($orderItem2);

        $this->assertEquals($omsConfig->getInitialStatus(), $orderItem1->getState()->getName());
        $this->assertEquals($omsConfig->getInitialStatus(), $orderItem2->getState()->getName());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getBaseQuoteTransfer(): QuoteTransfer
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore($storeTransfer);

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

        $this->tester->haveAvailabilityConcrete($productConcrete1->getSku(), $quoteTransfer->getStore(), 1);
        $this->tester->haveAvailabilityConcrete($productConcrete2->getSku(), $quoteTransfer->getStore(), 1);

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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getBaseQuoteTransferWithItemLevelShippingAddresses(): QuoteTransfer
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $currencyTransfer = (new CurrencyBuilder())->seed([
            CurrencyTransfer::CODE => 'EUR',
        ])->build();
        $quoteTransfer = (new QuoteBuilder())->build();
        $quoteTransfer->setStore($storeTransfer);
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

        $this->tester->haveAvailabilityConcrete($productConcrete1->getSku(), $quoteTransfer->getStore(), 3);
        $this->tester->haveAvailabilityConcrete($productConcrete2->getSku(), $quoteTransfer->getStore(), 3);

        $shippingAddress = (new AddressBuilder([
            AddressTransfer::ADDRESS2 => '84',
            AddressTransfer::ZIP_CODE => '12346',
            AddressTransfer::EMAIL => 'max@mustermann.de',
            AddressTransfer::CITY => 'Entenhausen2',
        ]))->build();

        $shipment = (new ShipmentBuilder())
            ->build();
        $shipment->setShippingAddress($shippingAddress);

        $item1 = (new ItemBuilder())
            ->seed([
                ItemTransfer::UNIT_PRICE => 4000,
                ItemTransfer::SKU => 'OSB1337',
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::UNIT_GROSS_PRICE => 3000,
                ItemTransfer::SUM_GROSS_PRICE => 3000,
                ItemTransfer::NAME => 'Product1',
            ])
            ->build();
        $item1->setShipment($shipment);
        $item2 = (new ItemBuilder())
            ->seed([
                ItemTransfer::UNIT_PRICE => 4000,
                ItemTransfer::SKU => 'OSB1338',
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::UNIT_GROSS_PRICE => 4000,
                ItemTransfer::SUM_GROSS_PRICE => 4000,
                ItemTransfer::NAME => 'Product2',
            ])
            ->build();
        $item2->setShipment($shipment);

        $quoteTransfer->addItem($item1);
        $quoteTransfer->addItem($item2);

        $totals = (new TotalsBuilder())->seed([
            TotalsTransfer::GRAND_TOTAL => 1000,
            TotalsTransfer::SUBTOTAL => 500,
        ])->build();

        $quoteTransfer->setTotals($totals);

        $billingAddress = (new AddressBuilder())->seed([
            AddressTransfer::ISO2_CODE => 'xi',
            AddressTransfer::EMAIL => 'max@mustermann.de',
        ])->build();

        $quoteTransfer->setBillingAddress($billingAddress);
        $customerTransfer = (new CustomerBuilder())->seed([
            CustomerTransfer::IS_GUEST => false,
            CustomerTransfer::EMAIL => $billingAddress->getEmail(),
        ])->build();

        $quoteTransfer->setCustomer($customerTransfer);

        $paymentTransfer = (new PaymentBuilder())->seed([
            PaymentTransfer::PAYMENT_SELECTION => 'no_payment',
        ])->build();

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

        $customerOrderSavePlugin = new CustomerOrderSavePlugin();
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

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer(): AddressTransfer
    {
        return (new AddressBuilder())->build();
    }

    /**
     * @param array $seed
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customer
     *
     * @return \Generated\Shared\DataBuilder\ItemBuilder
     */
    protected function createItemWithShipment(array $seed, ?CustomerTransfer $customer = null)
    {
        $address = (new AddressBuilder([AddressTransfer::EMAIL => $customer->getEmail()]));

        return (new ItemBuilder($seed))->withShipment(
            (new ShipmentBuilder())->withShippingAddress($address)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string[]
     */
    protected function getOrderItemsSkuList(OrderTransfer $orderTransfer): array
    {
        $orderItemsSkuList = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderItemsSkuList[$itemTransfer->getSku()] = $itemTransfer->getSku();
        }

        return $orderItemsSkuList;
    }
}
