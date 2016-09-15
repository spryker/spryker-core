<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Request;

use Functional\Spryker\Zed\Ratepay\Business\AbstractBusinessTest;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group AbstractFacadeTest
 */
abstract class AbstractFacadeTest extends AbstractBusinessTest
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
     */
    protected $converterFactory;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $orderEntity;

    /**
     * @var \Generated\Shared\Transfer\RatepayResponseTransfer|\Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    protected $responseTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayResponseTransfer|\Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    protected $expectedResponseTransfer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->converterFactory = new ConverterFactory();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface $adapter
     *
     * @return \Spryker\Zed\Ratepay\Business\RatepayFacade
     */
    protected function getFacadeMock(AdapterInterface $adapter)
    {
        return (new RatepayFacadeMockBuilder())->build($adapter, $this);
    }

    /**
     * @param \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\AbstractAdapterMock $adapterMock
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse
     */
    protected function sendRequest($adapterMock, $request)
    {
        return new BaseResponse($adapterMock->sendRequest($request));
    }

    /**
     * @return void
     */
    protected function setUpSalesOrderTestData()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');
        $billingAddress = new SpySalesOrderAddress();
        $billingAddress->fromArray($this->getAddressTransfer('billing')->toArray());
        $billingAddress->setFkCountry($country->getIdCountry())->save();

        $shippingAddress = new SpySalesOrderAddress();
        $shippingAddress->fromArray($this->getAddressTransfer('shipping')->toArray());
        $shippingAddress->setFkCountry($country->getIdCountry())->save();

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
            ->setOrderReference('TEST--1')
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($shippingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer);

        $this->orderEntity->save();
    }

    protected function setUpPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentRatepay())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder());
        $this->setRatepayPaymentEntityData($this->paymentEntity);
        $this->paymentEntity->save();
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $ratepayPaymentEntity
     *
     * @return void
     */
    abstract protected function setRatepayPaymentEntityData($ratepayPaymentEntity);

    /**
     * @return void
     */
    public function testPaymentWithSuccessResponse()
    {
        $adapterMock = $this->getPaymentSuccessResponseAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $this->responseTransfer = $this->runFacadeMethod($facade);

        $this->testResponseInstance();

        $expectedResponse = $this->sendRequest($adapterMock, $adapterMock->getSuccessResponse());
        $this->convertResponseToTransfer($expectedResponse);

        $this->assertEquals($this->expectedResponseTransfer, $this->responseTransfer);

        $this->assertSame($this->expectedResponseTransfer->getResultCode(), $this->responseTransfer->getResultCode());
        $this->assertSame($this->expectedResponseTransfer->getResultText(), $this->responseTransfer->getResultText());
        $this->assertSame($this->expectedResponseTransfer->getReasonCode(), $this->responseTransfer->getReasonCode());
        $this->assertSame($this->expectedResponseTransfer->getReasonText(), $this->responseTransfer->getReasonText());
        $this->assertSame($this->expectedResponseTransfer->getTransactionShortId(), $this->responseTransfer->getTransactionShortId());
        $this->assertSame($this->expectedResponseTransfer->getTransactionId(), $this->responseTransfer->getTransactionId());
        $this->assertSame($this->expectedResponseTransfer->getCustomerMessage(), $this->responseTransfer->getCustomerMessage());

        $this->assertSame($this->expectedResponseTransfer->getSuccessful(), $this->responseTransfer->getSuccessful());
        $this->assertTrue($this->expectedResponseTransfer->getSuccessful());
    }

    /**
     * @return void
     */
    public function testPaymentWithFailureResponse()
    {
        $adapterMock = $this->getPaymentFailureResponseAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $this->responseTransfer = $this->runFacadeMethod($facade);

        $this->testResponseInstance();

        $expectedResponse = $this->sendRequest($adapterMock, $adapterMock->getFailureResponse());
        $this->convertResponseToTransfer($expectedResponse);

        $this->assertEquals($this->expectedResponseTransfer, $this->responseTransfer);

        $this->assertSame($this->expectedResponseTransfer->getResultCode(), $this->responseTransfer->getResultCode());
        $this->assertSame($this->expectedResponseTransfer->getResultText(), $this->responseTransfer->getResultText());
        $this->assertSame($this->expectedResponseTransfer->getReasonCode(), $this->responseTransfer->getReasonCode());
        $this->assertSame($this->expectedResponseTransfer->getReasonText(), $this->responseTransfer->getReasonText());
        $this->assertSame($this->expectedResponseTransfer->getTransactionShortId(), $this->responseTransfer->getTransactionShortId());
        $this->assertSame($this->expectedResponseTransfer->getTransactionId(), $this->responseTransfer->getTransactionId());
        $this->assertSame($this->expectedResponseTransfer->getCustomerMessage(), $this->responseTransfer->getCustomerMessage());

        $this->assertSame($this->expectedResponseTransfer->getSuccessful(), $this->responseTransfer->getSuccessful());
        $this->assertFalse($this->expectedResponseTransfer->getSuccessful());
    }

    protected function testResponseInstance()
    {
        $this->assertInstanceOf('Generated\Shared\Transfer\RatepayResponseTransfer', $this->responseTransfer);
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse $expectedResponse
     *
     * @return void
     */
    protected function convertResponseToTransfer($expectedResponse)
    {
        $this->expectedResponseTransfer = $this->converterFactory
            ->getTransferObjectConverter($expectedResponse)
            ->convert();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    abstract protected function runFacadeMethod($facade);

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\AbstractAdapterMock
     */
    abstract protected function getPaymentSuccessResponseAdapterMock();

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\AbstractAdapterMock
     */
    abstract protected function getPaymentFailureResponseAdapterMock();

}
