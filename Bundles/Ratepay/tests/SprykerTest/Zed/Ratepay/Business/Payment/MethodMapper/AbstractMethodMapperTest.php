<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\ApiFactory;
use Spryker\Zed\Ratepay\Business\Api\Builder\BuilderFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm as DeliverConfirm;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Cancel;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Refund;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;
use SprykerTest\Zed\Ratepay\Business\Payment\BasePaymentTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group AbstractMethodMapperTest
 * Add your own group annotations below this line
 */
abstract class AbstractMethodMapperTest extends BasePaymentTest
{
    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected function createMapperFactory()
    {
        return new MapperFactory($this->requestTransfer);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\BuilderFactory
     */
    protected function createBuilderFactory()
    {
        return new BuilderFactory($this->requestTransfer);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    protected function createApiRequestFactory()
    {
        $factory = new ApiFactory($this->createBuilderFactory());

        return $factory->createRequestModelFactory();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($this->getTotalsTransfer())
            ->setBillingAddress($this->getAddressTransfer('billing'))
            ->setShippingAddress($this->getAddressTransfer('shipping'))
            ->setCustomer($this->getCustomerTransfer())
            ->setPayment($this->getPaymentTransfer())
            ->addItem($this->getItemTransfer(1))
            ->addItem($this->getItemTransfer(2));

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder('TEST--1')
            ->setOrderReference('TEST--1')
            ->setTotals($this->getTotalsTransfer())
            ->setBillingAddress($this->getAddressTransfer('billing'))
            ->setShippingAddress($this->getAddressTransfer('shipping'))
            ->setCustomer($this->getCustomerTransfer())
            ->addItem($this->getItemTransfer(1))
            ->addItem($this->getItemTransfer(2));

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\MethodInterface
     */
    abstract public function getPaymentMethod();

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    abstract protected function getPaymentTransfer();

    /**
     * @return void
     */
    public function testPaymentInit()
    {
        self::markTestSkipped();
        $paymentMethod = $this->getPaymentMethod();
        $request = $paymentMethod->paymentInit($this->mockRatepayPaymentInitTransfer());

        $this->assertInstanceOf(Init::class, $request);

        $this->assertEquals(Config::get(RatepayConstants::SYSTEM_ID), $this->requestTransfer->getHead()->getSystemId());
        $this->assertNotNull($this->requestTransfer->getHead()->getProfileId());
        $this->assertNotNull($this->requestTransfer->getHead()->getSecurityCode());
        $this->assertNull($this->requestTransfer->getHead()->getOperation());
        $this->assertEquals('58-201604122719694', $this->requestTransfer->getHead()->getTransactionId());
        $this->assertEquals('5QTZ.2VWD.OMWW.9D3E', $this->requestTransfer->getHead()->getTransactionShortId());
        $this->assertNull($this->requestTransfer->getHead()->getExternalOrderId());
        $this->assertNull($this->requestTransfer->getHead()->getOperationSubstring());
    }

    /**
     * @return void
     */
    public function testPaymentRequest()
    {
        self::markTestSkipped();
        $paymentMethod = $this->getPaymentMethod();

        $request = $paymentMethod->paymentRequest($this->mockRatepayPaymentRequestTransfer());

        $this->assertInstanceOf(Request::class, $request);
        $this->assertEquals(Config::get(RatepayConstants::SYSTEM_ID), $this->requestTransfer->getHead()->getSystemId());

        //head
        $this->assertNotNull($this->requestTransfer->getHead()->getProfileId());
        $this->assertNotNull($this->requestTransfer->getHead()->getSecurityCode());

        //customer data
        $this->assertEquals('email@site.com', $this->requestTransfer->getCustomer()->getEmail());
        $this->assertEquals('fn', $this->requestTransfer->getCustomer()->getFirstName());
        $this->assertEquals('ln', $this->requestTransfer->getCustomer()->getLastName());
        $this->assertEquals('m', $this->requestTransfer->getCustomer()->getGender());
        $this->assertEquals('yes', $this->requestTransfer->getCustomer()->getAllowCreditInquiry());
        $this->assertEquals('123456789', $this->requestTransfer->getCustomer()->getPhone());
        $this->assertNotNull($this->requestTransfer->getCustomer()->getIpAddress());

        //basket and items
        $this->testBasketAndItems();

        //payment
        $this->assertEquals('iso3', $this->requestTransfer->getPayment()->getCurrency());
        $this->assertEquals(18, $this->requestTransfer->getPayment()->getAmount());
        $this->testPaymentSpecificRequestData($request);
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    abstract protected function testPaymentSpecificRequestData($request);

    /**
     * @return void
     */
    public function testPaymentConfirm()
    {
        self::markTestSkipped();
        $paymentMethod = $this->getPaymentMethod();
        $request = $paymentMethod->paymentConfirm($this->getOrderTransfer());

        $this->assertInstanceOf(Confirm::class, $request);

        //head
        $this->assertNotNull($this->requestTransfer->getHead()->getProfileId());
        $this->assertNotNull($this->requestTransfer->getHead()->getSecurityCode());

        $this->assertEquals('TEST--1', $this->requestTransfer->getHead()->getExternalOrderId());
        $this->assertEquals(Config::get(RatepayConstants::SYSTEM_ID), $this->requestTransfer->getHead()->getSystemId());
    }

    /**
     * @return void
     */
    public function testDeliveryConfirm()
    {
        self::markTestSkipped();
        $paymentMethod = $this->getPaymentMethod();
        $orderTransfer = $this->getOrderTransfer();
        $partialOrderTransfer = $this->mockPartialOrderTransfer();
        $request = $paymentMethod->deliveryConfirm($orderTransfer, $partialOrderTransfer, $orderTransfer->getItems()->getArrayCopy());

        $this->assertInstanceOf(DeliverConfirm::class, $request);

        //head
        $this->assertNotNull($this->requestTransfer->getHead()->getProfileId());
        $this->assertNotNull($this->requestTransfer->getHead()->getSecurityCode());

        $this->assertEquals('58-201604122719694', $this->requestTransfer->getHead()->getTransactionId());
        $this->assertEquals('5QTZ.2VWD.OMWW.9D3E', $this->requestTransfer->getHead()->getTransactionShortId());

        $this->assertEquals(Config::get(RatepayConstants::SYSTEM_ID), $this->requestTransfer->getHead()->getSystemId());

        //basket and items
        $this->testBasketAndItems();
    }

    /**
     * @return void
     */
    public function testPaymentCancel()
    {
        self::markTestSkipped();
        $paymentMethod = $this->getPaymentMethod();
        $orderTransfer = $this->getOrderTransfer();
        $partialOrderTransfer = $this->mockPartialOrderTransfer();
        $request = $paymentMethod->paymentCancel($orderTransfer, $partialOrderTransfer, $orderTransfer->getItems()->getArrayCopy());

        $this->assertInstanceOf(Cancel::class, $request);

        //head
        $this->assertNotNull($this->requestTransfer->getHead()->getProfileId());
        $this->assertNotNull($this->requestTransfer->getHead()->getSecurityCode());

        $this->assertEquals('58-201604122719694', $this->requestTransfer->getHead()->getTransactionId());
        $this->assertEquals('5QTZ.2VWD.OMWW.9D3E', $this->requestTransfer->getHead()->getTransactionShortId());

        $this->assertEquals('TEST--1', $this->requestTransfer->getHead()->getExternalOrderId());
        $this->assertEquals(Config::get(RatepayConstants::SYSTEM_ID), $this->requestTransfer->getHead()->getSystemId());

        //basket and items
        $this->testBasketAndItems();
    }

    /**
     * @return void
     */
    public function testPaymentRefund()
    {
        self::markTestSkipped();
        $paymentMethod = $this->getPaymentMethod();
        $orderTransfer = $this->getOrderTransfer();
        $partialOrderTransfer = $this->mockPartialOrderTransfer();
        $request = $paymentMethod->paymentRefund($orderTransfer, $partialOrderTransfer, $orderTransfer->getItems()->getArrayCopy());

        $this->assertInstanceOf(Refund::class, $request);

        //head
        $this->assertNotNull($this->requestTransfer->getHead()->getProfileId());
        $this->assertNotNull($this->requestTransfer->getHead()->getSecurityCode());

        $this->assertEquals('58-201604122719694', $this->requestTransfer->getHead()->getTransactionId());
        $this->assertEquals('5QTZ.2VWD.OMWW.9D3E', $this->requestTransfer->getHead()->getTransactionShortId());

        $this->assertEquals('TEST--1', $this->requestTransfer->getHead()->getExternalOrderId());
        $this->assertEquals(Config::get(RatepayConstants::SYSTEM_ID), $this->requestTransfer->getHead()->getSystemId());

        //basket and items
        $this->testBasketAndItems();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer
            ->setEmail('test@test.com')
            ->setFirstName('John')
            ->setLastName('Doe');

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function getTotalsTransfer()
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(9900)
            ->setSubtotal(2000)
            ->setDiscountTotal(200)
            ->setExpenseTotal(0);

        return $totalsTransfer;
    }

    /**
     * @param string $itemPrefix
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddressTransfer($itemPrefix)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName($itemPrefix . 'John')
            ->setLastName($itemPrefix . 'Doe')
            ->setCity('Berlin')
            ->setIso2Code('DE')
            ->setAddress1($itemPrefix . 'Straße des 17. Juni')
            ->setAddress2($itemPrefix . '135')
            ->setAddress3($itemPrefix . '135')
            ->setZipCode($itemPrefix . '10623')
            ->setSalutation('Mr')
            ->setPhone($itemPrefix . '12345678');

        return $addressTransfer;
    }

    /**
     * @param string $itemPrefix
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemTransfer($itemPrefix)
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setName($itemPrefix . 'test')
            ->setSku($itemPrefix . '33333')
            ->setGroupKey($itemPrefix . '33333333333')
            ->setQuantity(3)
            ->setUnitGrossPrice(1000)
            ->setTaxRate('19')
            ->setUnitTotalDiscountAmountWithProductOption(100)
            ->setSumGrossPriceWithProductOptionAndDiscountAmounts(900)
            ->setUnitGrossPriceWithProductOptions(1000);

        return $itemTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface
     */
    protected function getQueryContainerMock()
    {
        $queryContainer = $this->getMockBuilder(RatepayQueryContainerInterface::class)->getMock();
        $queryPaymentsMock = $this->getPaymentRatepayQueryMock();
        $queryContainer->method('queryPayments')->willReturn($queryPaymentsMock);

        $queryPaymentLogMock = $this->getPaymentRatepayLogQueryMock();
        $queryContainer->method('queryPaymentLog')->willReturn($queryPaymentLogMock);

        return $queryContainer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    protected function getPaymentRatepayQueryMock()
    {
        $queryPaymentsMock = $this->getMockBuilder(SpyPaymentRatepayQuery::class)
            ->setMethods(['findByFkSalesOrder', 'getFirst', 'filterByMessage'])
            ->getMock();

        $ratepayPaymentEntity = new SpyPaymentRatepay();
        $salesOrder = new SpySalesOrder();
        $ratepayPaymentEntity->setSpySalesOrder($salesOrder);
        $this->setRatepayPaymentEntityData($ratepayPaymentEntity);

        $queryPaymentsMock->method('findByFkSalesOrder')->willReturnSelf();
        $queryPaymentsMock->method('filterByMessage')->willReturnSelf();
        $queryPaymentsMock->method('getFirst')->willReturn($ratepayPaymentEntity);

        return $queryPaymentsMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery
     */
    protected function getPaymentRatepayLogQueryMock()
    {
        $queryPaymentLogMock = $this->getMockBuilder(SpyPaymentRatepayLogQuery::class)
            ->setMethods(['findByFkSalesOrder', 'getData', 'filterByMessage'])
            ->getMock();

        $queryPaymentLogMock->method('findByFkSalesOrder')->willReturnSelf();
        $queryPaymentLogMock->method('filterByMessage')->willReturnSelf();
        $queryPaymentLogMock->method('getData')->willReturn([]);

        return $queryPaymentLogMock;
    }

    /**
     * @return void
     */
    protected function testBasketAndItems()
    {
        //Basket
        $this->assertContains($this->requestTransfer->getShoppingBasket()->getAmount(), ['58.00', '18.00']);
        $this->assertEquals('iso3', $this->requestTransfer->getShoppingBasket()->getCurrency());
        $this->assertEquals(0, (float)$this->requestTransfer->getShoppingBasket()->getShippingUnitPrice());
        $this->assertEquals(0, (float)$this->requestTransfer->getShoppingBasket()->getShippingTaxRate());
        $this->assertEquals('Shipping costs', $this->requestTransfer->getShoppingBasket()->getShippingTitle());
        $this->assertEquals(19, $this->requestTransfer->getShoppingBasket()->getDiscountTaxRate());
        $this->assertContains($this->requestTransfer->getShoppingBasket()->getDiscountUnitPrice(), [0, -2]);
        $this->assertEquals('Discount', $this->requestTransfer->getShoppingBasket()->getDiscountTitle());

        $this->assertArrayHasKey(0, $this->requestTransfer->getShoppingBasket()->getItems());

        //basketItems
        $basketItems = $this->requestTransfer->getShoppingBasket()->getItems();

        /*
         * @var \Generated\Shared\Transfer\RatepayRequestShoppingBasketItemTransfer $firstItem
         */
        $firstItem = $basketItems[0];
        $this->assertEquals('1test', $firstItem->getItemName());
        $this->assertEquals('133333', $firstItem->getArticleNumber());
        $this->assertEquals('133333333333', $firstItem->getUniqueArticleNumber());
        $this->assertEquals(3, $firstItem->getQuantity());
        $this->assertEquals(10, $firstItem->getUnitPriceGross());
        $this->assertEquals(19, $firstItem->getTaxRate());
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $ratepayPaymentEntity
     *
     * @return void
     */
    abstract protected function setRatepayPaymentEntityData($ratepayPaymentEntity);
}
