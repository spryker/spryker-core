<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PayolutionResponseTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Shared\Payolution\PayolutionApiConstants;
use SprykerFeature\Zed\Payolution\Business\Api\Response\Converter as ResponseConverter;
use SprykerFeature\Zed\Payolution\Business\PayolutionFacade;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLogQuery;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;

class AbstractFacadeTest extends Test
{

    /**
     * @var SpySalesOrder
     */
    private $orderEntity;

    /**
     * @var SpyPaymentPayolution
     */
    private $paymentEntity;

    /**
     * @var ResponseConverter
     */
    private $responseConverter;

    /**
     * @var SpyPaymentPayolutionTransactionRequestLogQuery
     */
    private $requestLogQuery;

    /**
     * @var SpyPaymentPayolutionTransactionStatusLogQuery
     */
    private $statusLogQuery;

    protected function _before()
    {
        parent::_before();
        $this->setUpSalesOrderTestData();
        $this->setUpPaymentTestData();
        $this->responseConverter = new ResponseConverter();
        $this->requestLogQuery = new SpyPaymentPayolutionTransactionRequestLogQuery();
        $this->statusLogQuery = new SpyPaymentPayolutionTransactionStatusLogQuery();
    }

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

        $customer = (new SpyCustomer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com')
            ->setDateOfBirth('1970-01-01')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setCustomerReference('payolution-pre-authorization-test');
        $customer->save();

        $this->orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setGrandTotal(10000)
            ->setSubtotal(10000)
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');
        $this->orderEntity->save();
    }

    private function setUpPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentPayolution())
            ->setFkSalesOrder($this->getOrderEntity()->getIdSalesOrder())
            ->setAccountBrand(PayolutionApiConstants::BRAND_INVOICE)
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
     * @return SpySalesOrder
     */
    protected function getOrderEntity()
    {
        return $this->orderEntity;
    }

    /**
     * @return SpyPaymentPayolution
     */
    protected function getPaymentEntity()
    {
        return $this->paymentEntity;
    }

    /**
     * @return ResponseConverter
     */
    protected function getResponseConverter()
    {
        return $this->responseConverter;
    }

    /**
     * @return SpyPaymentPayolutionTransactionRequestLogQuery
     */
    protected function getRequestLogQuery()
    {
        return $this->requestLogQuery;
    }

    /**
     * @return SpyPaymentPayolutionTransactionStatusLogQuery
     */
    protected function getStatusLogQuery()
    {
        return $this->statusLogQuery;
    }

    /**
     * @return SpyPaymentPayolutionTransactionRequestLog[]|ObjectCollection
     */
    protected function getRequestLogCollectionForPayment()
    {
        return $this
            ->getRequestLogQuery()
            ->findByFkPaymentPayolution($this->getPaymentEntity()->getIdPaymentPayolution());
    }

    /**
     * @return SpyPaymentPayolutionTransactionStatusLog[]|ObjectCollection
     */
    protected function getStatusLogCollectionForPayment()
    {
        return $this
            ->getStatusLogQuery()
            ->findByFkPaymentPayolution($this->getPaymentEntity()->getIdPaymentPayolution());
    }

    /**
     * @param AdapterInterface $adapter
     *
     * @return PayolutionFacade
     */
    protected function getFacadeMock(AdapterInterface $adapter)
    {
        return PayolutionFacadeMockBuilder::build($adapter, $this);
    }

    /**
     * @param SpyPaymentPayolutionTransactionStatusLog $statusLog
     * @param PayolutionResponseTransfer $response
     */
    protected function matchStatusLogWithResponse(
        SpyPaymentPayolutionTransactionStatusLog $statusLog,
        PayolutionResponseTransfer $response
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
