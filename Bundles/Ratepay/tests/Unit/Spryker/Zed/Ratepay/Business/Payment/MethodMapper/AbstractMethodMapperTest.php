<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayItemQuery;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLog;
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
use Unit\Spryker\Zed\Ratepay\Business\Payment\BasePaymentTest;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group AbstractMethodMapperTest
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
    protected  abstract function getPaymentTransfer();

    /**
     * @return void
     */
    public function testPaymentInit()
    {
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
        $paymentMethod = $this->getPaymentMethod();
        $orderTransfer = $this->getOrderTransfer();
        $request = $paymentMethod->deliveryConfirm($orderTransfer, $orderTransfer->getItems()->getArrayCopy());

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
        $paymentMethod = $this->getPaymentMethod();
        $orderTransfer = $this->getOrderTransfer();
        $request = $paymentMethod->paymentCancel($orderTransfer, $orderTransfer->getItems()->getArrayCopy());

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
        $paymentMethod = $this->getPaymentMethod();
        $orderTransfer = $this->getOrderTransfer();
        $request = $paymentMethod->paymentRefund($orderTransfer, $orderTransfer->getItems()->getArrayCopy());

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

    protected function getCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer
            ->setEmail('test@test.com')
            ->setFirstName('John')
            ->setLastName('Doe');

        return $customerTransfer;
    }

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
     * @return \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface
     */
    protected function getQueryContainerMock()
    {
        $queryContainer = $this->getMock(RatepayQueryContainerInterface::class);

        $queryPaymentsMock = $this->getMock(SpyPaymentRatepayQuery::class, ['findByFkSalesOrder', 'getFirst', 'filterByMessage']);

        $ratepayPaymentEntity = new SpyPaymentRatepay();
        $salesOrder = new SpySalesOrder();
        $ratepayPaymentEntity->setSpySalesOrder($salesOrder);
        $this->setRatepayPaymentEntityData($ratepayPaymentEntity);

        $queryPaymentsMock->method('findByFkSalesOrder')->willReturnSelf();
        $queryPaymentsMock->method('filterByMessage')->willReturnSelf();
        $queryPaymentsMock->method('getFirst')->willReturn($ratepayPaymentEntity);
        $queryContainer->method('queryPayments')->willReturn($queryPaymentsMock);

        $queryPaymentLogMock = $this->getMock(SpyPaymentRatepayLogQuery::class, ['findByFkSalesOrder', 'getData', 'filterByMessage']);
        $queryPaymentLogMock->method('findByFkSalesOrder')->willReturnSelf();
        $queryPaymentLogMock->method('filterByMessage')->willReturnSelf();
        $queryPaymentLogMock->method('getData')->willReturn([]);
        $queryContainer->method('queryPaymentLog')->willReturn($queryPaymentLogMock);


        $paymentRatepayItemQuery = $this->getMock(SpyPaymentRatepayItemQuery::class, ['findOne']);
        $paymentRatepayItemQuery->method('findOne')->willReturn(null);

        $queryContainer->method('queryPaymentItemByOrderIdAndSku')->willReturn($paymentRatepayItemQuery);
        $queryContainer->method('addPaymentItem')->willReturnSelf();

        return $queryContainer;
    }

    /**
     * @return void
     */
    protected function testBasketAndItems()
    {
        //Basket
        $this->assertContains($this->requestTransfer->getShoppingBasket()->getAmount(), ['54.00', '18.00']);
        $this->assertEquals('iso3', $this->requestTransfer->getShoppingBasket()->getCurrency());
        $this->assertEquals(0, (float)$this->requestTransfer->getShoppingBasket()->getShippingUnitPrice());
        $this->assertEquals(0, (float)$this->requestTransfer->getShoppingBasket()->getShippingTaxRate());
        $this->assertEquals('Shipping costs', $this->requestTransfer->getShoppingBasket()->getShippingTitle());
        $this->assertEquals(0, $this->requestTransfer->getShoppingBasket()->getDiscountTaxRate());
        $this->assertEquals(0, $this->requestTransfer->getShoppingBasket()->getDiscountUnitPrice());
        $this->assertEquals('Discount', $this->requestTransfer->getShoppingBasket()->getDiscountTitle());

        $this->assertArrayHasKey(0, $this->requestTransfer->getShoppingBasket()->getItems());

        //basketItems
        $basketItems = $this->requestTransfer->getShoppingBasket()->getItems();

        /**
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
