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
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Shared\CheckoutExtension\CheckoutExtensionContextsInterface;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\Availability\Communication\Plugin\ProductsAvailableCheckoutPreConditionPlugin;
use Spryker\Zed\Checkout\CheckoutConfig;
use Spryker\Zed\Checkout\CheckoutDependencyProvider;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface;
use Spryker\Zed\Customer\Communication\Plugin\Checkout\CustomerOrderSavePlugin;
use Spryker\Zed\Customer\Communication\Plugin\CustomerPreConditionCheckerPlugin;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderItemsSaverPlugin;
use Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderSaverPlugin;
use Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderTotalsSaverPlugin;
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
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\Checkout\CheckoutBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS, [
            new CustomerPreConditionCheckerPlugin(),
            new ProductsAvailableCheckoutPreConditionPlugin(),
        ]);

        $this->tester->setDependency(CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS, [
            $this->createOrderSaverPlugin(),
            $this->createOrderTotalsSaverPlugin(),
            $this->createOrderItemsSaverPlugin(),
            $this->createCustomerOrderSavePlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testCheckoutSuccessfully(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer);
        $email = 'random.customer@spryker.com';
        $itemBuilder = (new ItemBuilder([ItemTransfer::SKU => $productTransfer->getSku(), ItemTransfer::UNIT_PRICE => 1]))->withShipment(
            (new ShipmentBuilder())->withShippingAddress([AddressTransfer::EMAIL => $email]),
        );

        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemBuilder)
            ->withStore($storeTransfer->toArray())
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertTrue($result->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutSuccessfullyWithItemLevelShippingAddresses(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer, 3);
        $email = 'random.customer@spryker.com';
        $itemBuilder = (new ItemBuilder([ItemTransfer::SKU => $productTransfer->getSku(), ItemTransfer::UNIT_PRICE => 1]))->withShipment(
            (new ShipmentBuilder())->withShippingAddress([AddressTransfer::EMAIL => $email]),
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

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertTrue($result->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfCustomerAlreadyRegistered(): void
    {
        // Arrange
        $this->tester->haveCustomer([CustomerTransfer::EMAIL => 'max@mustermann.de']);
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer);
        $customer = $this->tester->haveCustomer();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::SKU => $productTransfer->getSku()])
            ->withStore($storeTransfer->toArray())
            ->withCustomer([CustomerTransfer::EMAIL => 'max@mustermann.de'])
            ->withTotals()
            ->withCurrency()
            ->withBillingAddress()
            ->withShippingAddress(new AddressBuilder([AddressTransfer::EMAIL => $customer->getEmail()]))
            ->build();

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertFalse($result->getIsSuccess());
        $this->assertSame(1, count($result->getErrors()));
        $this->assertSame(CheckoutConfig::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfCustomerAlreadyRegisteredWithItemLevelShippingAddress(): void
    {
        // Arrange
        $this->tester->haveCustomer([CustomerTransfer::EMAIL => 'max@mustermann.de']);
        $productTransfer = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer);
        $customer = $this->tester->haveCustomer();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem($this->createItemWithShipment([ItemTransfer::SKU => $productTransfer->getSku()], $customer))
            ->withStore($storeTransfer->toArray())
            ->withCustomer([CustomerTransfer::EMAIL => 'max@mustermann.de'])
            ->withTotals()
            ->withCurrency()
            ->withBillingAddress()
            ->build();

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertFalse($result->getIsSuccess());
        $this->assertCount(1, $result->getErrors());
        $this->assertSame(CheckoutConfig::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutCreatesOrderItemsWithItemLevelShippingAddresses(): void
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
        $this->assertSame(0, count($result->getErrors()));

        $salesFacade = $this->tester->getLocator()->sales()->facade();
        $orderTransfer = $salesFacade->getOrderByIdSalesOrder($result->getSaveOrder()->getIdSalesOrder());
        $orderItemsSkuList = $this->getOrderItemsSkuList($orderTransfer);

        $this->assertSame(2, $orderTransfer->getItems()->count());
        $this->assertArrayHasKey($productTransfer1->getSku(), $orderItemsSkuList);
        $this->assertArrayHasKey($productTransfer2->getSku(), $orderItemsSkuList);
    }

    /**
     * @return void
     */
    public function testRegistrationIsTriggeredOnNewNonGuestCustomerWithItemLevelShippingAddresses(): void
    {
        // Arrange
        $quoteTransfer = $this->getBaseQuoteTransferWithItemLevelShippingAddresses();

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertTrue($result->getIsSuccess());
        $this->assertSame(0, count($result->getErrors()));

        $customerQuery = SpyCustomerQuery::create()->filterByEmail($quoteTransfer->getCustomer()->getEmail());
        $this->assertSame(1, $customerQuery->count());
    }

    /**
     * @return void
     */
    public function testRegistrationDoesNotCreateACustomerIfGuestWithItemLevelShippingAddresses(): void
    {
        // Arrange
        $quoteTransfer = $this->getBaseQuoteTransferWithItemLevelShippingAddresses();
        $quoteTransfer->getCustomer()->setIsGuest(true);

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertTrue($result->getIsSuccess());
        $this->assertSame(0, count($result->getErrors()));

        $customerQuery = SpyCustomerQuery::create()->filterByEmail($quoteTransfer->getCustomer()->getEmail());
        $this->assertSame(0, $customerQuery->count());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfStockNotSufficient(): void
    {
        // Arrange
        $quoteTransfer = $this->getBaseQuoteTransfer();
        $productConcreteTransfer1 = $this->tester->haveProduct([ProductConcreteTransfer::SKU => 'OSB1339'], [ProductAbstractTransfer::SKU => 'AOSB1339']);

        $quoteTransfer->addItem(
            $this->createItemTransfer($productConcreteTransfer1, 2),
        );

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertFalse($result->getIsSuccess());
        $this->assertSame(1, count($result->getErrors()));
        $this->assertSame(CheckoutConfig::ERROR_CODE_PRODUCT_UNAVAILABLE, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfOutOfStock(): void
    {
        // Arrange
        $quoteTransfer = $this->getBaseQuoteTransfer();
        $productConcrete = $this->tester->haveProduct();

        $quoteTransfer->addItem(
            $this->createItemTransfer($productConcrete, 2),
        );

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertFalse($result->getIsSuccess());
        $this->assertSame(1, count($result->getErrors()));
        $this->assertSame(CheckoutConfig::ERROR_CODE_PRODUCT_UNAVAILABLE, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfStockNotSufficientWithItemLevelShippingAddresses(): void
    {
        // Arrange
        $quoteTransfer = $this->getBaseQuoteTransferWithItemLevelShippingAddresses();
        $productConcreteTransfer1 = $this->tester->haveProduct();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveAvailabilityConcrete($productConcreteTransfer1->getSku(), $storeTransfer, 1);

        $quoteTransfer->addItem(
            $this->createItemTransfer($productConcreteTransfer1, 2)
                ->setShipment($this->createShipmentTransfer()),
        );

        // Act
        $result = $this->tester->getFacade()->placeOrder($quoteTransfer);

        // Assert
        $this->assertFalse($result->getIsSuccess());
        $this->assertSame(1, count($result->getErrors()));
        $this->assertSame(CheckoutConfig::ERROR_CODE_PRODUCT_UNAVAILABLE, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutTriggersStateMachineWithItemLevelShippingAddresses(): void
    {
        // Arrange
        $quoteTransfer = $this->getBaseQuoteTransferWithItemLevelShippingAddresses();

        // Act
        $this->tester->getFacade()->placeOrder($quoteTransfer);

        $omsConfig = new OmsConfig();

        $orderItem1 = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337')
            ->findOne();
        $orderItem2 = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338')
            ->findOne();

        // Assert
        $this->assertNotNull($orderItem1);
        $this->assertNotNull($orderItem2);

        $this->assertEquals($omsConfig->getInitialStatus(), $orderItem1->getState()->getName());
        $this->assertEquals($omsConfig->getInitialStatus(), $orderItem2->getState()->getName());
    }

    /**
     * @dataProvider placeOrderExecutesPluginStack
     *
     * @param array<string, list<string>> $dependencyMap
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testPlaceOrderExecutesPluginStack(
        array $dependencyMap,
        QuoteTransfer $quoteTransfer
    ): void {
        // Arrange, Assert
        foreach ($dependencyMap as $dependencyKey => $createDependencyMethodNames) {
            $dependency = [];
            foreach ($createDependencyMethodNames as $createDependencyMethodName) {
                $dependency[] = $this->$createDependencyMethodName();
            }

            $this->tester->setDependency($dependencyKey, $dependency);
        }

        // Act
        $this->tester->getFacade()->placeOrder($quoteTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getBaseQuoteTransfer(): QuoteTransfer
    {
        // Arrange
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

        $productConcreteTransfer1 = $this->tester->haveProduct([ProductConcreteTransfer::SKU => 'OSB1337'], [ProductAbstractTransfer::SKU => 'AOSB1337']);
        $productConcreteTransfer2 = $this->tester->haveProduct([ProductConcreteTransfer::SKU => 'OSB1338'], [ProductAbstractTransfer::SKU => 'AOSB1338']);

        $this->tester->haveAvailabilityConcrete($productConcreteTransfer1->getSku(), $storeTransfer, 1);
        $this->tester->haveAvailabilityConcrete($productConcreteTransfer2->getSku(), $storeTransfer, 1);

        $itemTransfer1 = $this->createItemTransfer($productConcreteTransfer1, 1);
        $itemTransfer2 = $this->createItemTransfer($productConcreteTransfer2, 1);

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);

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
        $quoteTransfer->addPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getBaseQuoteTransferWithItemLevelShippingAddresses(): QuoteTransfer
    {
        // Arrange
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

        $productConcreteTransfer1 = $this->tester->haveProduct([ProductConcreteTransfer::SKU => 'OSB1337'], [ProductAbstractTransfer::SKU => 'AOSB1337']);
        $productConcreteTransfer2 = $this->tester->haveProduct([ProductConcreteTransfer::SKU => 'OSB1338'], [ProductAbstractTransfer::SKU => 'AOSB1338']);

        $this->tester->haveAvailabilityConcrete($productConcreteTransfer1->getSku(), $storeTransfer);
        $this->tester->haveAvailabilityConcrete($productConcreteTransfer2->getSku(), $storeTransfer, 3);

        $shipment = $this->createShipmentTransfer();

        $itemTransfer1 = $this->createItemTransfer($productConcreteTransfer1, 1);
        $itemTransfer1->setShipment($shipment);
        $itemTransfer2 = $this->createItemTransfer($productConcreteTransfer2, 1);
        $itemTransfer2->setShipment($shipment);

        $quoteTransfer->addItem($itemTransfer1);
        $quoteTransfer->addItem($itemTransfer2);

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

        $quoteTransfer->addPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipmentTransfer(): ShipmentTransfer
    {
        $shippingAddress = (new AddressBuilder([
            AddressTransfer::ADDRESS2 => '84',
            AddressTransfer::ZIP_CODE => '12346',
            AddressTransfer::EMAIL => 'max@mustermann.de',
            AddressTransfer::CITY => 'Entenhausen',
        ]))->build();

        $shipment = (new ShipmentBuilder())
            ->build();
        $shipment->setShippingAddress($shippingAddress);

        return $shipment;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer(ProductConcreteTransfer $productConcreteTransfer, int $quantity): ItemTransfer
    {
        return (new ItemBuilder())
            ->seed([
                ItemTransfer::UNIT_PRICE => 4000,
                ItemTransfer::SKU => $productConcreteTransfer->getSku(),
                ItemTransfer::QUANTITY => $quantity,
                ItemTransfer::UNIT_GROSS_PRICE => 3000,
                ItemTransfer::SUM_GROSS_PRICE => 3000,
                ItemTransfer::NAME => 'ProductName',
            ])
            ->build();
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderSaverPlugin
     */
    protected function createOrderSaverPlugin(): OrderSaverPlugin
    {
        $orderSaverPlugin = new OrderSaverPlugin();
        $orderSaverPlugin->setFacade($this->createSalesFacadeMock());

        return $orderSaverPlugin;
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderTotalsSaverPlugin
     */
    protected function createOrderTotalsSaverPlugin(): OrderTotalsSaverPlugin
    {
        $orderTotalsSaverPlugin = new OrderTotalsSaverPlugin();
        $orderTotalsSaverPlugin->setFacade($this->createSalesFacadeMock());

        return $orderTotalsSaverPlugin;
    }

    /**
     * @return \Spryker\Zed\Sales\Communication\Plugin\Checkout\OrderItemsSaverPlugin
     */
    protected function createOrderItemsSaverPlugin(): OrderItemsSaverPlugin
    {
        $orderItemsSaverPlugin = new OrderItemsSaverPlugin();
        $orderItemsSaverPlugin->setFacade($this->createSalesFacadeMock());

        return $orderItemsSaverPlugin;
    }

    /**
     * @return \Spryker\Zed\Customer\Communication\Plugin\Checkout\CustomerOrderSavePlugin
     */
    protected function createCustomerOrderSavePlugin(): CustomerOrderSavePlugin
    {
        $customerOrderSavePlugin = new CustomerOrderSavePlugin();
        $customerOrderSavePlugin->setFacade($this->tester->getCustomerFacade());

        return $customerOrderSavePlugin;
    }

    /**
     * @param string $testStateMachineProcessName
     *
     * @return \Spryker\Zed\Sales\Business\SalesFacade
     */
    protected function createSalesFacadeMock(string $testStateMachineProcessName = 'Test01'): SalesFacade
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
    protected function createItemWithShipment(array $seed, ?CustomerTransfer $customer = null): ItemBuilder
    {
        $address = (new AddressBuilder([AddressTransfer::EMAIL => $customer->getEmail()]));

        return (new ItemBuilder($seed))->withShipment(
            (new ShipmentBuilder())->withShippingAddress($address),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<string>
     */
    protected function getOrderItemsSkuList(OrderTransfer $orderTransfer): array
    {
        $orderItemsSkuList = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderItemsSkuList[$itemTransfer->getSku()] = $itemTransfer->getSku();
        }

        return $orderItemsSkuList;
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function placeOrderExecutesPluginStack(): array
    {
        $checkoutContext = CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT;
        $orderAmendmentContext = SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT;
        $orderAmendmentAsyncContext = SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT_ASYNC;
        $wrongContext = 'something-else-context';

        return [
            'Executes CheckoutPreConditionPluginInterface stack when flow name is not set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => ['getCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                new QuoteTransfer(),
            ],
            'Executes CheckoutPreConditionPluginInterface stack when flow name is set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => ['getCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($checkoutContext)),
            ],
            'Executes CheckoutPreSavePluginInterface stack when flow name is not set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => ['getCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                new QuoteTransfer(),
            ],
            'Executes CheckoutPreSavePluginInterface stack when flow name is set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => ['getCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($checkoutContext)),
            ],
            'Executes CheckoutDoSaveOrderInterface stack when flow name is not set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => ['getCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                new QuoteTransfer(),
            ],
            'Executes CheckoutDoSaveOrderInterface stack when flow name is set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => ['getCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($checkoutContext)),
            ],
            'Executes CheckoutPostSaveInterface stack when flow name is not set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => ['getCheckoutPostSavePluginMock'],
                ],
                new QuoteTransfer(),
            ],
            'Executes CheckoutPostSaveInterface stack when flow name is set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => ['getCheckoutPostSavePluginMock'],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($checkoutContext)),
            ],
            'Executes default CheckoutPreConditionPluginInterface plugins when flow name is not set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => ['getCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS_FOR_ORDER_AMENDMENT => ['getNeverCalledCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                new QuoteTransfer(),
            ],
            'Executes default CheckoutPreConditionPluginInterface plugins when flow is not found by name' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => ['getCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS_FOR_ORDER_AMENDMENT => ['getNeverCalledCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($wrongContext)),
            ],
            'Executes CheckoutPreConditionPluginInterface plugins for order amendment flow' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => ['getNeverCalledCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS_FOR_ORDER_AMENDMENT => ['getCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($orderAmendmentContext)),
            ],
            'Executes CheckoutPreConditionPluginInterface plugins for order amendment async flow' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => ['getNeverCalledCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS_FOR_ORDER_AMENDMENT_ASYNC => ['getCheckoutPreConditionPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($orderAmendmentAsyncContext)),
            ],
            'Executes default CheckoutPreSavePluginInterface plugins when flow name is not set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => ['getCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS_FOR_ORDER_AMENDMENT => ['getNeverCalledCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                new QuoteTransfer(),
            ],
            'Executes default CheckoutPreSavePluginInterface plugins when flow is not found by name' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => ['getCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS_FOR_ORDER_AMENDMENT => ['getNeverCalledCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($wrongContext)),
            ],
            'Executes CheckoutPreSavePluginInterface plugins for order amendment flow' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => ['getNeverCalledCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS_FOR_ORDER_AMENDMENT => ['getCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($orderAmendmentContext)),
            ],
            'Executes CheckoutPreSavePluginInterface plugins for order amendment async flow' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => ['getNeverCalledCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS_FOR_ORDER_AMENDMENT_ASYNC => ['getCheckoutPreSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($orderAmendmentAsyncContext)),
            ],
            'Executes default CheckoutDoSaveOrderInterface plugins when flow name is not set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => ['getCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS_FOR_ORDER_AMENDMENT => ['getNeverCalledCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                new QuoteTransfer(),
            ],
            'Executes default CheckoutDoSaveOrderInterface plugins when flow is not found by name' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => ['getCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS_FOR_ORDER_AMENDMENT => ['getNeverCalledCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($wrongContext)),
            ],
            'Executes CheckoutDoSaveOrderInterface plugins for order amendment flow' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => ['getNeverCalledCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS_FOR_ORDER_AMENDMENT => ['getCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($orderAmendmentContext)),
            ],
            'Executes CheckoutDoSaveOrderInterface plugins for order amendment async flow' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => ['getNeverCalledCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS_FOR_ORDER_AMENDMENT_ASYNC => ['getCheckoutDoSaveOrderPluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => [],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($orderAmendmentAsyncContext)),
            ],
            'Executes default CheckoutPostSaveInterface plugins when flow name is not set' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => ['getCheckoutPostSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS_FOR_ORDER_AMENDMENT => ['getNeverCalledCheckoutPostSavePluginMock'],
                ],
                new QuoteTransfer(),
            ],
            'Executes default CheckoutPostSaveInterface plugins when flow is not found by name' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => ['getCheckoutPostSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS_FOR_ORDER_AMENDMENT => ['getNeverCalledCheckoutPostSavePluginMock'],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($wrongContext)),
            ],
            'Executes CheckoutPostSaveInterface plugins for order amendment flow' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => ['getNeverCalledCheckoutPostSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS_FOR_ORDER_AMENDMENT => ['getCheckoutPostSavePluginMock'],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($orderAmendmentContext)),
            ],
            'Executes CheckoutPostSaveInterface plugins for order amendment async flow' => [
                [
                    CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS => [],
                    CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS => [],
                    CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS => [],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS => ['getNeverCalledCheckoutPostSavePluginMock'],
                    CheckoutDependencyProvider::CHECKOUT_POST_HOOKS_FOR_ORDER_AMENDMENT_ASYNC => ['getCheckoutPostSavePluginMock'],
                ],
                (new QuoteTransfer())->setQuoteProcessFlow((new QuoteProcessFlowTransfer())->setName($orderAmendmentAsyncContext)),
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface
     */
    protected function getCheckoutPreConditionPluginMock(): CheckoutPreConditionPluginInterface
    {
        $checkoutPreConditionPluginMock = $this
            ->getMockBuilder(CheckoutPreConditionPluginInterface::class)
            ->getMock();
        $checkoutPreConditionPluginMock->expects($this->once())->method('checkCondition');

        return $checkoutPreConditionPluginMock;
    }

    /**
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface
     */
    protected function getNeverCalledCheckoutPreConditionPluginMock(): CheckoutPreConditionPluginInterface
    {
        $checkoutPreConditionPluginMock = $this
            ->getMockBuilder(CheckoutPreConditionPluginInterface::class)
            ->getMock();
        $checkoutPreConditionPluginMock->expects($this->never())->method('checkCondition');

        return $checkoutPreConditionPluginMock;
    }

    /**
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface
     */
    protected function getCheckoutPreSavePluginMock(): CheckoutPreSavePluginInterface
    {
        $checkoutPreSavePluginMock = $this
            ->getMockBuilder(CheckoutPreSavePluginInterface::class)
            ->getMock();
        $checkoutPreSavePluginMock->method('preSave')->willReturn(new QuoteTransfer());
        $checkoutPreSavePluginMock->expects($this->once())->method('preSave');

        return $checkoutPreSavePluginMock;
    }

    /**
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreSavePluginInterface
     */
    protected function getNeverCalledCheckoutPreSavePluginMock(): CheckoutPreSavePluginInterface
    {
        $checkoutPreSavePluginMock = $this
            ->getMockBuilder(CheckoutPreSavePluginInterface::class)
            ->getMock();
        $checkoutPreSavePluginMock->expects($this->never())->method('preSave');

        return $checkoutPreSavePluginMock;
    }

    /**
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface
     */
    protected function getCheckoutDoSaveOrderPluginMock(): CheckoutDoSaveOrderInterface
    {
        $checkoutDoSaveOrderPluginMock = $this
            ->getMockBuilder(CheckoutDoSaveOrderInterface::class)
            ->getMock();
        $checkoutDoSaveOrderPluginMock->expects($this->once())->method('saveOrder');

        return $checkoutDoSaveOrderPluginMock;
    }

    /**
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface
     */
    protected function getNeverCalledCheckoutDoSaveOrderPluginMock(): CheckoutDoSaveOrderInterface
    {
        $checkoutDoSaveOrderPluginMock = $this
            ->getMockBuilder(CheckoutDoSaveOrderInterface::class)
            ->getMock();
        $checkoutDoSaveOrderPluginMock->expects($this->never())->method('saveOrder');

        return $checkoutDoSaveOrderPluginMock;
    }

    /**
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface
     */
    protected function getCheckoutPostSavePluginMock(): CheckoutPostSaveInterface
    {
        $checkoutPostSavePluginMock = $this
            ->getMockBuilder(CheckoutPostSaveInterface::class)
            ->getMock();
        $checkoutPostSavePluginMock->expects($this->once())->method('executeHook');

        return $checkoutPostSavePluginMock;
    }

    /**
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPostSaveInterface
     */
    protected function getNeverCalledCheckoutPostSavePluginMock(): CheckoutPostSaveInterface
    {
        $checkoutPostSavePluginMock = $this
            ->getMockBuilder(CheckoutPostSaveInterface::class)
            ->getMock();
        $checkoutPostSavePluginMock->expects($this->never())->method('executeHook');

        return $checkoutPostSavePluginMock;
    }
}
