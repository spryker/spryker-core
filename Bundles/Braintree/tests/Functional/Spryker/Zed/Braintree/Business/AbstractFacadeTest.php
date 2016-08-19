<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Braintree\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Braintree\Business\BraintreeFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Braintree
 * @group Business
 * @group AbstractFacadeTest
 */
class AbstractFacadeTest extends Test
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
     * @var \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery
     */
    protected $requestLogQuery;

    /**
     * @var \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    protected $statusLogQuery;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();
        $this->setUpSalesOrderTestData();
        $this->setUpPaymentTestData();
        $this->requestLogQuery = new SpyPaymentBraintreeTransactionRequestLogQuery();
        $this->statusLogQuery = new SpyPaymentBraintreeTransactionStatusLogQuery();
    }

    /**
     * @return void
     */
    protected function setUpSalesOrderTestData()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');

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
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(1000);
        $orderTransfer->setTotals($totalsTransfer);
        $orderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());

        return $orderTransfer;
    }

    /**
     * @return void
     */
    private function setUpPaymentTestData()
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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getOrderEntity()
    {
        return $this->orderEntity;
    }

    /**
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree
     */
    protected function getPaymentEntity()
    {
        return $this->paymentEntity;
    }

    /**
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLogQuery
     */
    protected function getRequestLogQuery()
    {
        return $this->requestLogQuery;
    }

    /**
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLogQuery
     */
    protected function getStatusLogQuery()
    {
        return $this->statusLogQuery;
    }

    /**
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLog[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getRequestLogCollectionForPayment()
    {
        return $this
            ->getRequestLogQuery()
            ->findByFkPaymentBraintree($this->getPaymentEntity()->getIdPaymentBraintree());
    }

    /**
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLog[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getStatusLogCollectionForPayment()
    {
        return $this
            ->getStatusLogQuery()
            ->findByFkPaymentBraintree($this->getPaymentEntity()->getIdPaymentBraintree());
    }

    protected function getFacade()
    {
        return new BraintreeFacade();
    }

}
