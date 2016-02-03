<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Payolution\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Zed\Payolution\Business\Api\Converter\Converter as ResponseConverter;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;

class AbstractFacadeTest extends Test
{

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    private $orderEntity;

    /**
     * @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    private $paymentEntity;

    /**
     * @var \Spryker\Zed\Payolution\Business\Api\Converter\Converter
     */
    private $responseConverter;

    /**
     * @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery
     */
    private $requestLogQuery;

    /**
     * @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    private $statusLogQuery;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();
        $this->setUpSalesOrderTestData();
        $this->setUpPaymentTestData();
        $this->responseConverter = new ResponseConverter();
        $this->requestLogQuery = new SpyPaymentPayolutionTransactionRequestLogQuery();
        $this->statusLogQuery = new SpyPaymentPayolutionTransactionStatusLogQuery();
    }

    /**
     * @return void
     */
    protected function setUpSalesOrderTestData()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('de');

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
            ->filterByCustomerReference('payolution-pre-authorization-test')
            ->findOneOrCreate();

        $customer->save();

        $this->orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setGrandTotal(1000)
            ->setSubtotal(1000)
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
    private function setUpPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentPayolution())
            ->setFkSalesOrder($this->getOrderEntity()->getIdSalesOrder())
            ->setAccountBrand(PayolutionConstants::BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setDateOfBirth('1970-01-02')
            ->setEmail('jane@family-doe.org')
            ->setGender(SpyPaymentPayolutionTableMap::COL_GENDER_MALE)
            ->setSalutation(SpyPaymentPayolutionTableMap::COL_SALUTATION_MR)
            ->setCountryIso2Code('de')
            ->setCity('Berlin')
            ->setStreet('Straße des 17. Juni 135')
            ->setZipCode('10623')
            ->setLanguageIso2Code('de')
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
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    protected function getPaymentEntity()
    {
        return $this->paymentEntity;
    }

    /**
     * @return \Spryker\Zed\Payolution\Business\Api\Converter\Converter
     */
    protected function getResponseConverter()
    {
        return $this->responseConverter;
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery
     */
    protected function getRequestLogQuery()
    {
        return $this->requestLogQuery;
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    protected function getStatusLogQuery()
    {
        return $this->statusLogQuery;
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getRequestLogCollectionForPayment()
    {
        return $this
            ->getRequestLogQuery()
            ->findByFkPaymentPayolution($this->getPaymentEntity()->getIdPaymentPayolution());
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getStatusLogCollectionForPayment()
    {
        return $this
            ->getStatusLogQuery()
            ->findByFkPaymentPayolution($this->getPaymentEntity()->getIdPaymentPayolution());
    }

    /**
     * @param \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface $adapter
     *
     * @return \Spryker\Zed\Payolution\Business\PayolutionFacade
     */
    protected function getFacadeMock(AdapterInterface $adapter)
    {
        return PayolutionFacadeMockBuilder::build($adapter, $this);
    }

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $statusLog
     * @param \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer $response
     *
     * @return void
     */
    protected function matchStatusLogWithResponse(
        SpyPaymentPayolutionTransactionStatusLog $statusLog,
        PayolutionTransactionResponseTransfer $response
    ) {
        $this->assertEquals($response->getProcessingCode(), $statusLog->getProcessingCode());
        $this->assertEquals($response->getProcessingResult(), $statusLog->getProcessingResult());
        $this->assertEquals($response->getProcessingStatus(), $statusLog->getProcessingStatus());
        $this->assertEquals($response->getProcessingStatusCode(), $statusLog->getProcessingStatusCode());
        $this->assertEquals($response->getProcessingReason(), $statusLog->getProcessingReason());
        $this->assertEquals($response->getProcessingReasonCode(), $statusLog->getProcessingReasonCode());
        $this->assertEquals($response->getProcessingReturn(), $statusLog->getProcessingReturn());
        $this->assertEquals($response->getProcessingReturnCode(), $statusLog->getProcessingReturnCode());
        $this->assertNotNull($statusLog->getIdentificationTransactionid());
        $this->assertNotNull($statusLog->getIdentificationUniqueid());
        $this->assertNotNull($statusLog->getIdentificationShortid());
        $this->assertNotNull($statusLog->getProcessingTimestamp());
    }

}
