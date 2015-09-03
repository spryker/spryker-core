<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Checkout\Business;

use Codeception\TestCase\Test;
use Functional\SprykerFeature\Zed\Checkout\Dependency\MockOmsOrderHydrator;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Checkout\CheckoutConfig;
use SprykerFeature\Zed\Checkout\Business\CheckoutFacade;
use SprykerFeature\Zed\Checkout\CheckoutDependencyProvider;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountry;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Oms\OmsConfig;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStock;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockProduct;

/**
 * @group SprykerFeature
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
     * @param string $bundleName
     *
     * @return \SprykerEngine\Zed\Kernel\Communication\Factory
     */
    protected function getCommunicationFactory($bundleName)
    {
        return new \SprykerEngine\Zed\Kernel\Communication\Factory($bundleName);
    }

    protected function setUp()
    {
        parent::setUp();
        $locator = Locator::getInstance();

        $this->checkoutFacade = new CheckoutFacade(new Factory('Checkout'), $locator);

        $container = new Container();

        $container[CheckoutDependencyProvider::CHECKOUT_PRECONDITIONS] = function (Container $container) {

            return [
                $container->getLocator()->customerCheckoutConnector()->pluginCustomerPreconditionCheckerPlugin(),
                $container->getLocator()->availabilityCheckoutConnector()->pluginProductsAvailablePreconditionPlugin(),
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_ORDERHYDRATORS] = function (Container $container) {

            return [
                $container->getLocator()->customerCheckoutConnector()->pluginOrderCustomerHydrationPlugin(),
                $container->getLocator()->cartCheckoutConnector()->pluginOrderCartHydrationPlugin(),
                new MockOmsOrderHydrator(),
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_ORDERSAVERS] = function (Container $container) {

            return [
                $container->getLocator()->salesCheckoutConnector()->pluginSalesOrderSaverPlugin(),
                $container->getLocator()->customerCheckoutConnector()->pluginOrderCustomerSavePlugin(),
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_POSTHOOKS] = function (Container $container) use ($locator) {

            return [

            ];
        };

        $container[CheckoutDependencyProvider::FACADE_OMS] = function (Container $container) {
            return $container->getLocator()->oms()->facade();
        };

        $this->checkoutFacade->setExternalDependencies($container);
    }

    /**
     * @todo move this code to customer checkout connector, registration can only happen if we have
     * already installed customer bundle
     */
    public function testRegistrationIsTriggeredOnNewNonGuestCustomer()
    {
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
     */
    public function testRegistrationDoesNotCreateACustomerIfGuest()
    {
        $checkoutRequest = $this->getBaseCheckoutTransfer();
        $checkoutRequest->setGuest(true);

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $customerQuery = SpyCustomerQuery::create()->filterByEmail($checkoutRequest->getEmail());
        $this->assertEquals(0, $customerQuery->count());
    }

    public function testCheckoutResponseContainsErrorIfCustomerAlreadyRegistered()
    {
        $customer = new SpyCustomer();
        $customer
            ->setCustomerReference('TestCustomer1')
            ->setEmail('max@mustermann.de')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setPassword('MyPass')
            ->save()
        ;

        $checkoutRequest = $this->getBaseCheckoutTransfer();

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertFalse($result->getIsSuccess());
        $this->assertEquals(1, count($result->getErrors()));
        $this->assertEquals(CheckoutConfig::ERROR_CODE_CUSTOMER_ALREADY_REGISTERED, $result->getErrors()[0]->getErrorCode());
    }

    public function testCheckoutCreatesOrderItems()
    {
        $checkoutRequest = $this->getBaseCheckoutTransfer();

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertTrue($result->getIsSuccess());
        $this->assertEquals(0, count($result->getErrors()));

        $orderItem1Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337')
        ;
        $orderItem2Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338')
        ;

        $this->assertEquals(1, $orderItem1Query->count());
        $this->assertEquals(1, $orderItem2Query->count());
    }

    public function testCheckoutResponseContainsErrorIfStockNotSufficient()
    {
        $checkoutRequest = $this->getBaseCheckoutTransfer();
        $abstractProduct1 = new SpyAbstractProduct();
        $abstractProduct1
            ->setSku('AOSB1339')
            ->setAttributes('{}')
        ;
        $concreteProduct1 = new SpyProduct();
        $concreteProduct1
            ->setSku('OSB1339')
            ->setAttributes('{}')
            ->setSpyAbstractProduct($abstractProduct1)
            ->save()
        ;

        $stock = new SpyStock();
        $stock
            ->setName('Stock2')
        ;

        $stock1 = new SpyStockProduct();
        $stock1
            ->setQuantity(1)
            ->setStock($stock)
            ->setSpyProduct($concreteProduct1)
            ->save()
        ;

        $item = new ItemTransfer();
        $item
            ->setSku('OSB1339')
            ->setQuantity(2)
            ->setPriceToPay(1000)
            ->setGrossPrice(3000)
        ;

        $checkoutRequest->getCart()->addItem($item);

        $result = $this->checkoutFacade->requestCheckout($checkoutRequest);

        $this->assertFalse($result->getIsSuccess());
        $this->assertEquals(1, count($result->getErrors()));
        $this->assertEquals(CheckoutConfig::ERROR_CODE_PRODUCT_UNAVAILABLE, $result->getErrors()[0]->getErrorCode());
    }

    public function testCheckoutTriggersStateMachine()
    {
        $checkoutRequest = $this->getBaseCheckoutTransfer();

        $this->checkoutFacade->requestCheckout($checkoutRequest);

        $orderItem1Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1337')
        ;

        $orderItem2Query = SpySalesOrderItemQuery::create()
            ->filterBySku('OSB1338')
        ;

        $orderItem1 = $orderItem1Query->findOne();
        $orderItem2 = $orderItem2Query->findOne();

        $this->assertNotNull($orderItem1);
        $this->assertNotNull($orderItem2);

        $this->assertNotEquals(OmsConfig::INITIAL_STATUS, $orderItem1->getState()->getName());
        $this->assertEquals('request to pay sent', $orderItem2->getState()->getName());
    }

    /**
     * @return CheckoutRequestTransfer
     */
    protected function getBaseCheckoutTransfer()
    {
        $country = new SpyCountry();
        $country
            ->setIso2Code('xi')
            ->save()
        ;

        $abstractProduct1 = new SpyAbstractProduct();
        $abstractProduct1
            ->setSku('AOSB1337')
            ->setAttributes('{}')
        ;
        $concreteProduct1 = new SpyProduct();
        $concreteProduct1
            ->setSku('OSB1337')
            ->setAttributes('{}')
            ->setSpyAbstractProduct($abstractProduct1)
            ->save()
        ;

        $abstractProduct2 = new SpyAbstractProduct();
        $abstractProduct2
            ->setSku('AOSB1338')
            ->setAttributes('{}')
        ;
        $concreteProduct2 = new SpyProduct();
        $concreteProduct2
            ->setSku('OSB1338')
            ->setSpyAbstractProduct($abstractProduct2)
            ->setAttributes('{}')
            ->save()
        ;

        $stock = new SpyStock();
        $stock
            ->setName('testStock')
        ;

        $stock1 = new SpyStockProduct();
        $stock1
            ->setQuantity(1)
            ->setStock($stock)
            ->setSpyProduct($concreteProduct1)
            ->save()
        ;

        $stock2 = new SpyStockProduct();
        $stock2
            ->setQuantity(1)
            ->setStock($stock)
            ->setSpyProduct($concreteProduct2)
            ->save()
        ;

        $item1 = new ItemTransfer();
        $item1
            ->setSku('OSB1337')
            ->setQuantity(1)
            ->setPriceToPay(1000)
            ->setGrossPrice(3000)
            ->setName('Product1')
            ->setTaxSet(new TaxSetTransfer())
        ;

        $item2 = new ItemTransfer();
        $item2
            ->setSku('OSB1338')
            ->setQuantity(1)
            ->setPriceToPay(2000)
            ->setGrossPrice(4000)
            ->setName('Product2')
            ->setTaxSet(new TaxSetTransfer())
        ;

        $cart = new CartTransfer();
        $cart->addItem($item1);
        $cart->addItem($item2);

        $totals = new TotalsTransfer();
        $totals
            ->setGrandTotal(1000)
            ->setGrandTotalWithDiscounts(800)
            ->setSubtotal(500)
        ;

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
            ->setCity('Entenhausen')
        ;
        $shippingAddress
            ->setIso2Code('xi')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setEmail('max@mustermann.de')
            ->setAddress1('Straße')
            ->setAddress2('84')
            ->setZipCode('12346')
            ->setCity('Entenhausen2')
        ;

        $checkoutRequest = new CheckoutRequestTransfer();
        $checkoutRequest
            ->setGuest(false)
            ->setEmail('max@mustermann.de')
            ->setIdUser(null)
            ->setCart($cart)
            ->setBillingAddress($billingAddress)
            ->setShippingAddress($shippingAddress)
            ->setPaymentMethod('creditcard')
        ;

        return $checkoutRequest;
    }

}
