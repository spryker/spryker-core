<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\PayolutionOmsConnector\Communication\Plugin\Command;

use Codeception\TestCase\Test;
use Functional\SprykerFeature\Zed\PayolutionOmsConnector\Communication\Plugin\Command\Mock\OmsFacade;
use Functional\SprykerFeature\Zed\PayolutionOmsConnector\Communication\Plugin\Command\Mock\OmsOrderHydrator;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Payolution\PayolutionConfigConstants;
use SprykerFeature\Zed\Checkout\CheckoutDependencyProvider;
use SprykerFeature\Zed\Checkout\Business\CheckoutFacade;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountry;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStock;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockProduct;

class PreAuthorizePluginTest extends Test
{


    public function testCheckoutEventTransition()
    {
        (new SpyCountry())
            ->setIso2Code('xi')
            ->save();

        $abstractProduct = (new SpyAbstractProduct())
            ->setSku('0987654321')
            ->setAttributes('{}');

        $concreteProduct = (new SpyProduct())
            ->setSku('1234567890')
            ->setAttributes('{}')
            ->setSpyAbstractProduct($abstractProduct);
        $concreteProduct->save();

        $stock = (new SpyStock())->setName('testStock');

        (new SpyStockProduct())
            ->setQuantity(1)
            ->setStock($stock)
            ->setSpyProduct($concreteProduct)
            ->save();

        $itemTransfer = (new ItemTransfer())
            ->setSku('1234567890')
            ->setQuantity(1)
            ->setPriceToPay(10000)
            ->setGrossPrice(10000 * 1.19)
            ->setName('Socken')
            ->setTaxSet(new TaxSetTransfer());

        $totalsTransfer = (new TotalsTransfer())
            ->setGrandTotal(10000)
            ->setGrandTotalWithDiscounts(10000)
            ->setSubtotal(10000);

        $cartTransfer = (new CartTransfer())
            ->addItem($itemTransfer)
            ->setTotals($totalsTransfer);

        $billingAddressTransfer = (new AddressTransfer())
            ->setIso2Code('xi')
            ->setEmail('john@doe.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623')
            ->setCity('Berlin');

        $shippingAddressTransfer = (new AddressTransfer())
            ->setIso2Code('xi')
            ->setEmail('john@doe.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Fraunhoferstraße')
            ->setAddress2('120')
            ->setZipCode('80469')
            ->setCity('München');


        $checkoutRequestTransfer = (new CheckoutRequestTransfer())
            ->setGuest(false)
            ->setIdUser(null)
            ->setShippingAddress($shippingAddressTransfer)
            ->setBillingAddress($billingAddressTransfer)
            ->setPaymentMethod('invoice')
            ->setCart($cartTransfer);


        $payment = new PayolutionPaymentTransfer();
        $payment->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation(SpyCustomerTableMap::COL_SALUTATION_MR)
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setEmail('testst@tewst.com')
            ->setBirthdate('1970-01-02')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(PayolutionConfigConstants::BRAND_INVOICE);

        $checkoutRequestTransfer->setPayolutionPayment($payment);


        $checkoutFacade = $this->getCheckoutFacade();
        $checkoutFacade->requestCheckout($checkoutRequestTransfer);

        $orderItem = SpySalesOrderItemQuery::create()->findOne();

        $this->assertEquals('waiting for payolution payment', $orderItem->getState()->getName());
    }

    /**
     * @return CheckoutFacade
     */
    private function getCheckoutFacade()
    {
        $checkoutFacade = $this->getLocator()->checkout()->facade();

        $container = new Container();

        $container[CheckoutDependencyProvider::CHECKOUT_PRECONDITIONS] = function (Container $container) {
            return [
                $container->getLocator()->customerCheckoutConnector()->pluginCustomerPreconditionCheckerPlugin(),
                $container->getLocator()->availabilityCheckoutConnector()->pluginProductsAvailablePreconditionPlugin(),
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_ORDERHYDRATORS] = function(Container $container) {
            return [
                $container->getLocator()->customerCheckoutConnector()->pluginOrderCustomerHydrationPlugin(),
                $container->getLocator()->cartCheckoutConnector()->pluginOrderCartHydrationPlugin(),
                $container->getLocator()->payolutionCheckoutConnector()->pluginCheckoutOrderHydrationPlugin(),
                new OmsOrderHydrator(),
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_ORDERSAVERS] = function (Container $container) {
            return [
                $container->getLocator()->salesCheckoutConnector()->pluginSalesOrderSaverPlugin(),
                $container->getLocator()->customerCheckoutConnector()->pluginOrderCustomerSavePlugin(),
                $container->getLocator()->payolutionCheckoutConnector()->pluginCheckoutSaveOrderPlugin(),
            ];
        };

        $container[CheckoutDependencyProvider::CHECKOUT_POSTHOOKS] = function (Container $container) {
            return [];
        };

        $container[CheckoutDependencyProvider::FACADE_OMS] = function (Container $container) {
            $facade = $container->getLocator()->oms()->facade();

            return $facade;
        };

        $checkoutFacade->setExternalDependencies($container);

        return $checkoutFacade;
    }

    /**
     * @return AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
