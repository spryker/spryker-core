<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;


use Codeception\TestCase\Test;

use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\AdapterMock;
use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\PreAuthorizationAdapterMock;
use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\PreCheckAdapterMock;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Persistence\Factory as PersistenceFactory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Business\Api\Response\Converter;
use SprykerFeature\Zed\Payolution\Business\PayolutionFacade;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainer;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

class PayolutionFacadeWithMockAdapterTest extends Test
{

    /**
     * @var SpySalesOrder
     */
    private $orderEntity;

    /**
     * @var SpyPaymentPayolution
     */
    private $paymentEntity;

    public function testPreCheckPaymentSuccess()
    {
        $adapterMock = new PreCheckAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preCheckPayment($this->getOrderTransfer());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = (new Converter())->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertSame($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
    }

    public function testPreCheckFailure()
    {
        $adapterMock = (new PreCheckAdapterMock())->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preCheckPayment($this->getOrderTransfer());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = (new Converter())->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertSame($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
    }

    /**
     * @return OrderTransfer
     */
    private function getOrderTransfer()
    {
        $totalsTransfer = (new TotalsTransfer())->setGrandTotal(100000);

        $addressTransfer = (new AddressTransfer())
            ->setCity('Berlin')
            ->setZipCode('10623')
            ->setAddress1('Straße des 17. Juni 135')
            ->setIso2Code('de');

        $paymentTransfer = (new PayolutionPaymentTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setGender('Male')
            ->setSalutation('Mr')
            ->setBirthdate('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setEmail('john@doe.com')
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE);

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder(1)
            ->setPayolutionPayment($paymentTransfer)
            ->setBillingAddress($addressTransfer)
            ->setTotals($totalsTransfer);

        return $orderTransfer;
    }

    public function testPreAuthorizePaymentSuccess()
    {
        $this->setBaseTestData();
        $this->setPaymentTestData();

        $adapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = (new Converter())->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals(0, $expectedResponse->getProcessingRiskScore());
        $this->assertEquals('ACK', $expectedResponse->getP3Validation());
        $this->assertEquals('John', $expectedResponse->getNameGiven());
        $this->assertEquals('158', $expectedResponse->getIdentificationShopperid());
        $this->assertEquals('2214.7311.2738 inv-ins-test-default 179', $expectedResponse->getClearingDescriptor());
        $this->assertEquals('Tx-cgwebcjwuk4', $expectedResponse->getProcessingConnectordetailConnectortxid1());
        $this->assertEquals('8a82941832d84c500132e875fc0c0648', $expectedResponse->getTransactionChannel());
        $this->assertEquals('00', $expectedResponse->getProcessingReasonCode());
        $this->assertEquals('Berlin', $expectedResponse->getAddressCity());
        $this->assertEquals('false', $expectedResponse->getFrontendRequestCancelled());
        $this->assertEquals('VA.PA.90.00', $expectedResponse->getProcessingCode());
        $this->assertEquals('Successful Processing', $expectedResponse->getProcessingReason());
        $this->assertEquals('DEFAULT', $expectedResponse->getFrontendMode());
        $this->assertEquals('INTERN', $expectedResponse->getClearingFxsource());
        $this->assertEquals('100.00', $expectedResponse->getClearingAmount());
        $this->assertEquals('ACK', $expectedResponse->getProcessingResult());
        $this->assertEquals('MR', $expectedResponse->getNameSalutation());
        $this->assertEquals('179', $expectedResponse->getPresentationUsage());
        $this->assertEquals('ACK', $expectedResponse->getPostValidation());
        $this->assertEquals('john@doe.com', $expectedResponse->getContactEmail());
        $this->assertEquals('EUR', $expectedResponse->getClearingCurrency());
        $this->assertEquals('', $expectedResponse->getFrontendSessionId());
        $this->assertEquals('90', $expectedResponse->getProcessingStatusCode());
        $this->assertEquals('EUR', $expectedResponse->getPresentationCurrency());
        $this->assertEquals('VA.PA', $expectedResponse->getPaymentCode());
        $this->assertEquals('1970-01-01', $expectedResponse->getNameBirthdate());
        $this->assertEquals('000.100.112', $expectedResponse->getProcessingReturnCode());
        $this->assertEquals('127.0.0.1', $expectedResponse->getContactIp());
        $this->assertEquals('Doe', $expectedResponse->getNameFamily());
        $this->assertEquals('NEW', $expectedResponse->getProcessingStatus());
        $this->assertEquals('images/visa_mc.gif', $expectedResponse->getFrontendCcLogo());
        $this->assertEquals('100.00', $expectedResponse->getPresentationAmount());
        $this->assertEquals('8a82944a4fbc48bb014fbd1f3a544ace', $expectedResponse->getIdentificationUniqueid());
        $this->assertEquals('2214.7311.2738', $expectedResponse->getIdentificationShortid());
        $this->assertEquals('1.0', $expectedResponse->getClearingFxrate());
        $this->assertEquals('2015-09-11 15:56:26', $expectedResponse->getProcessingTimestamp());
        $this->assertEquals('DE', $expectedResponse->getAddressCountry());
        $this->assertEquals('RSRX-BWHY-JLDN', $expectedResponse->getProcessingConnectordetailPaymentreference());
        $this->assertEquals('1.0', $expectedResponse->getResponseVersion());
        $this->assertEquals('CONNECTOR_TEST', $expectedResponse->getTransactionMode());
        $this->assertEquals(
            'Request successfully processed in \'Merchant in Connector Test Mode\'',
            $expectedResponse->getProcessingReturn()
        );
        $this->assertEquals('SYNC', $expectedResponse->getTransactionResponse());
        $this->assertEquals('Straße des 17. Juni 135', $expectedResponse->getAddressStreet());
        $this->assertEquals('M', $expectedResponse->getNameSex());
        $this->assertEquals('2015-09-11 15:56:24', $expectedResponse->getClearingFxdate());
        $this->assertEquals('10623', $expectedResponse->getAddressZip());

    }

    public function testPreAuthorizePaymentFailure()
    {

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $adapterMock = (new PreAuthorizationAdapterMock())->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);

        $response = $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = (new Converter())->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @return PayolutionFacade
     */
    private function getFacadeMock(AdapterInterface $adapter)
    {
        $factory = new Factory('Payolution');
        $locator = Locator::getInstance();

        $queryContainer = new PayolutionQueryContainer(new PersistenceFactory('Payolution'), $locator);


        $dependencyContainerMock = $this->getMock(
            'SprykerFeature\Zed\Payolution\Business\PayolutionDependencyContainer',
            ['createExecutionAdapter'],
            [
                $factory,
                $locator,
                new PayolutionConfig(Config::getInstance(), $locator)
            ]
        );

        $dependencyContainerMock->setQueryContainer($queryContainer);

        $dependencyContainerMock
            ->expects($this->any())
            ->method('createExecutionAdapter')
            ->will($this->returnValue($adapter));

        $facade = $this->getMock(
            'SprykerFeature\Zed\Payolution\Business\PayolutionFacade',
            ['getDependencyContainer'],
            [$factory, $locator]);

        $facade->expects($this->any())
            ->method('getDependencyContainer')
            ->will($this->returnValue($dependencyContainerMock));

        return $facade;
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function setBaseTestData()
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

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function setPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentPayolution())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setBirthdate('1970-01-02')
            ->setEmail('jane@family-doe.org')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setSalutation(SpyCustomerTableMap::COL_SALUTATION_MR);
        $this->paymentEntity->save();
    }

}
