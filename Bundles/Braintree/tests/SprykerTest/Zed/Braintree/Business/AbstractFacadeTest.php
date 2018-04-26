<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Braintree\Business;

use Braintree\Result\Error;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\TransactionMetaTransfer;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Braintree\BraintreeDependencyProvider;
use Spryker\Zed\Braintree\Business\BraintreeBusinessFactory;
use Spryker\Zed\Braintree\Business\BraintreeFacade;
use Spryker\Zed\Braintree\Persistence\BraintreeQueryContainer;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Braintree
 * @group Business
 * @group Facade
 * @group AbstractFacadeTest
 * Add your own group annotations below this line
 */
class AbstractFacadeTest extends Unit
{
    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $orderEntity;

    /**
     * @var \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree
     */
    protected $paymentEntity;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->setUpSalesOrderTestData();
        $this->setUpPaymentTestData();
    }

    /**
     * @param \Spryker\Zed\Braintree\Business\BraintreeBusinessFactory|null $braintreeBusinessFactoryMock
     *
     * @return \Spryker\Zed\Braintree\Business\BraintreeFacade
     */
    protected function getBraintreeFacade(?BraintreeBusinessFactory $braintreeBusinessFactoryMock = null)
    {
        $braintreeFacade = new BraintreeFacade();
        if ($braintreeBusinessFactoryMock) {
            $braintreeFacade->setFactory($braintreeBusinessFactoryMock);
        }

        return $braintreeFacade;
    }

    /**
     * @return \Braintree\Result\Error
     */
    protected function getErrorResponse()
    {
        $response = new Error(['errors' => [], 'message' => 'Error']);

        return $response;
    }

    /**
     * @return void
     */
    protected function setUpSalesOrderTestData()
    {
        $country = SpyCountryQuery::create()->filterByIso2Code('DE')->findOneOrCreate();
        $country->save();

        $billingAddress = (new SpySalesOrderAddress())
            ->setFkCountry($country->getIdCountry())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $billingAddress->save();

        $customer = (new SpyCustomerQuery())
            ->filterByFirstName('John')
            ->filterByLastName('Doe')
            ->filterByEmail('john@doe.com')
            ->filterByDateOfBirth('1970-01-01')
            ->filterByGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->filterByCustomerReference('braintree-pre-authorization-test')
            ->findOneOrCreate();

        $customer->save();

        $this->orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');

        $this->orderEntity->save();
    }

    /**
     * @return void
     */
    protected function setUpPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentBraintree())
            ->setFkSalesOrder($this->getOrderEntity()->getIdSalesOrder())
            ->setPaymentType(BraintreeConstants::METHOD_PAY_PAL)
            ->setTransactionId('abc')
            ->setClientIp('127.0.0.1')
            ->setEmail('jane@family-doe.org')
            ->setCountryIso2Code('DE')
            ->setCity('Berlin')
            ->setStreet('Straße des 17. Juni 135')
            ->setZipCode('10623')
            ->setLanguageIso2Code('DE')
            ->setCurrencyIso3Code('EUR');
        $this->paymentEntity->save();
    }

    /**
     * @return \Generated\Shared\Transfer\TransactionMetaTransfer
     */
    protected function getTransactionMetaTransfer()
    {
        $transactionMetaTransfer = new TransactionMetaTransfer();
        $transactionMetaTransfer->setIdSalesOrder($this->getOrderEntity()->getIdSalesOrder());

        return $transactionMetaTransfer;
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Braintree\Business\BraintreeBusinessFactory
     */
    protected function getFactoryMock(array $methods)
    {
        $factoryMock = $this->getFactory($methods);
        $factoryMock->setContainer($this->getContainer());
        $factoryMock->setQueryContainer(new BraintreeQueryContainer());
        $factoryMock->setConfig(new BraintreeConfig());

        return $factoryMock;
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Braintree\Business\BraintreeBusinessFactory
     */
    protected function getFactory(array $methods)
    {
        $factoryMock = $this->getMockBuilder(BraintreeBusinessFactory::class)->setMethods($methods)->getMock();

        return $factoryMock;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        $container = new Container();
        $braintreeDependencyProvider = new BraintreeDependencyProvider();
        $braintreeDependencyProvider->provideBusinessLayerDependencies($container);

        return $container;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(1000);
        $orderTransfer->setTotals($totalsTransfer);
        $orderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());

        $addressTransfer = new AddressTransfer();
        $orderTransfer->setBillingAddress($addressTransfer);
        $orderTransfer->setShippingAddress($addressTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getOrderEntity()
    {
        return $this->orderEntity;
    }
}
