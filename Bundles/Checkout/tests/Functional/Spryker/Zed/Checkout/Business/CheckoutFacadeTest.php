<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Checkout\Business;

use Codeception\TestCase\Test;
use Functional\Spryker\Zed\Checkout\Dependency\MockOmsOrderHydrator;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Checkout\Business\CheckoutBusinessFactory;
use Spryker\Zed\Kernel\Container;
use Spryker\Shared\Checkout\CheckoutConstants;
use Spryker\Zed\Checkout\Business\CheckoutFacade;
use Spryker\Zed\Checkout\CheckoutDependencyProvider;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsBridge;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;

/**
 * @group Spryker
 * @group Zed
 * @group Business
 * @group CheckoutFacadeTest
 */
class CheckoutFacadeTest extends Test
{

    /**
     * @var CheckoutFacade
     */
    protected $checkoutFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->checkoutFacade = new CheckoutFacade();

        $factoryMock = $this->getFactory();
        $this->checkoutFacade->setFactory($factoryMock);
    }

    /**
     * @todo move this code to customer checkout connector, registration can only happen if we have
     * already installed customer bundle
     *
     * @return void
     */
    public function testRegistrationIsTriggeredOnNewNonGuestCustomer()
    {
        $this->markTestSkipped();
        $checkoutRequest = $this->getBaseCheckoutTransfer();

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $customerQuery = SpyCustomerQuery::create()->filterByEmail($checkoutRequest->getEmail());
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
        $checkoutRequest = $this->getBaseCheckoutTransfer();
        $checkoutRequest->setIsGuest(true);

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $customerQuery = SpyCustomerQuery::create()->filterByEmail($checkoutRequest->getEmail());
        $this->assertEquals(0, $customerQuery->count());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfCustomerAlreadyRegistered()
    {
        $this->markTestSkipped();
        $customer = new SpyCustomer();
        $customer
            ->setCustomerReference('TestCustomer1')
            ->setEmail('max@mustermann.de')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setPassword('MyPass')
            ->save();

        $checkoutRequest = $this->getBaseCheckoutTransfer();

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertFalse($result->getIsSuccess());
        $this->assertEquals(1, count($result->getErrors()));
        $this->assertEquals(CheckoutConstants::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutCreatesOrderItems()
    {
        $this->markTestSkipped();
        $checkoutRequest = $this->getBaseCheckoutTransfer();

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $orderItem1Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337');
        $orderItem2Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338');

        $this->assertEquals(1, $orderItem1Query->count());
        $this->assertEquals(1, $orderItem2Query->count());
    }

    /**
     * @return void
     */
    public function testCheckoutResponseContainsErrorIfStockNotSufficient()
    {
        $this->markTestSkipped();
        $checkoutRequest = $this->getBaseCheckoutTransfer();
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
            ->setPriceToPay(1000)
            ->setGrossPrice(3000);

        $checkoutRequest->getCart()->addItem($item);

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertFalse($result->getIsSuccess());
        $this->assertEquals(1, count($result->getErrors()));
        $this->assertEquals(CheckoutConstants::ERROR_CODE_PRODUCT_UNAVAILABLE, $result->getErrors()[0]->getErrorCode());
    }

    /**
     * @return void
     */
    public function testCheckoutTriggersStateMachine()
    {
        $this->markTestSkipped();
        $checkoutRequest = $this->getBaseCheckoutTransfer();

        $this->checkoutFacade->requestCheckout($checkoutRequest);

        $orderItem1Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337');

        $orderItem2Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338');

        $orderItem1 = $orderItem1Query->findOne();
        $orderItem2 = $orderItem2Query->findOne();

        $this->assertNotNull($orderItem1);
        $this->assertNotNull($orderItem2);

        $this->assertNotEquals('new', $orderItem1->getState()->getName());
        $this->assertEquals('waiting for payment', $orderItem2->getState()->getName());
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutRequestTransfer
     */
    protected function getBaseCheckoutTransfer()
    {
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

        $stock = new SpyStock();
        $stock
            ->setName('testStock');

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
            ->setSku('OSB1337')
            ->setQuantity(1)
            ->setPriceToPay(1000)
            ->setGrossPrice(3000)
            ->setName('Product1')
            ->setTaxSet(new TaxSetTransfer());

        $item2 = new ItemTransfer();
        $item2
            ->setSku('OSB1338')
            ->setQuantity(1)
            ->setPriceToPay(2000)
            ->setGrossPrice(4000)
            ->setName('Product2')
            ->setTaxSet(new TaxSetTransfer());

        $cart = new CartTransfer();
        $cart->addItem($item1);
        $cart->addItem($item2);

        $totals = new TotalsTransfer();
        $totals
            ->setGrandTotal(1000)
            ->setGrandTotalWithDiscounts(800)
            ->setSubtotal(500);

        $cart->setTotals($totals);

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

        $checkoutRequest = new CheckoutRequestTransfer();
        $checkoutRequest
            ->setIsGuest(false)
            ->setEmail('max@mustermann.de')
            ->setIdUser(null)
            ->setCart($cart)
            ->setBillingAddress($billingAddress)
            ->setShippingAddress($shippingAddress)
            ->setPaymentMethod('creditcard');

        return $checkoutRequest;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        $container = new Container();

        $container[CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS] = function (Container $container) {
            return [];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_ORDER_HYDRATORS] = function (Container $container) {
            return [
                new MockOmsOrderHydrator(),
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_PRE_HYDRATOR] = function (Container $container) {
            return [];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS] = function (Container $container) {
            return [];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_POST_HOOKS] = function (Container $container) {
            return [];
        };

        $container[CheckoutDependencyProvider::FACADE_OMS] = function (Container $container) {
            return new CheckoutToOmsBridge($container->getLocator()->oms()->facade());
        };

        return $container;
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

}
