<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Payment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayRequestShoppingBasketTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Money\Business\MoneyFacade;
use Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Ratepay\Business\Api\Builder\Head;
use Spryker\Zed\Ratepay\Business\Api\Builder\InstallmentCalculation;
use Spryker\Zed\Ratepay\Business\Api\Builder\Payment;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\QuotePaymentRequestMapper;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Installment;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Invoice;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyBridge;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;
use SprykerTest\Zed\Ratepay\Business\Api\Response\Response;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Payment
 * @group BasePaymentTest
 * Add your own group annotations below this line
 */
class BasePaymentTest extends Unit
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected $mapperFactory;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestTransfer = new RatepayRequestTransfer();
        $this->requestTransfer->setShoppingBasket(new RatepayRequestShoppingBasketTransfer())
            ->getShoppingBasket()
            ->setShippingTitle('Shipping costs');
        $this->mapperFactory = new MapperFactory($this->requestTransfer);
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->requestTransfer);
        unset($this->mapperFactory);
    }

    /**
     * @param string $className
     * @param array $additionalMockMethods
     *
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\QuoteTransactionInterface|\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\OrderTransactionInterface|\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\PaymentInitTransactionInterface|\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\RequestPaymentTransaction
     */
    protected function getTransactionHandlerObject($className, $additionalMockMethods = [])
    {
        $additionalMethods = [];
        foreach ($additionalMockMethods as $method => $return) {
            $additionalMethods[] = $method;
        }

        $executionAdapter = $this->getMockBuilder(Guzzle::class)
            ->disableOriginalConstructor()
            ->setMethods(array_merge(['sendRequest'], $additionalMethods))
            ->getMock();

        $executionAdapter->method('sendRequest')
            ->willReturn((new Response())->getTestPaymentConfirmResponseData());

        foreach ($additionalMockMethods as $method => $return) {
            $executionAdapter->method($method)
                ->willReturn($return);
        }

        $ratepayToMoneyBridge = new RatepayToMoneyBridge(new MoneyFacade());
        $converterFactory = new ConverterFactory($ratepayToMoneyBridge);

        $transactionHandler = $this->getMockBuilder($className)
            ->setConstructorArgs([
                $executionAdapter,
                $converterFactory,
                $this->mockRatepayQueryContainer(),
            ])
            ->setMethods(array_merge(['logInfo'], $additionalMethods))
            ->getMock();

        $transactionHandler->method('logInfo')
            ->willReturn(null);

        foreach ($additionalMockMethods as $method => $return) {
            $transactionHandler->method($method)
                ->willReturn($return);
        }

        return $transactionHandler;
    }

    /**
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function mockPaymentRatepay()
    {
        $spyPaymentRatepay = $this->getMockBuilder(SpyPaymentRatepay::class)
            ->disableOriginalConstructor()
            ->getMock();

        $spyPaymentRatepay->method('getPaymentType')
            ->willReturn(RatepayConstants::INVOICE);

        $spyPaymentRatepay->method('setResultCode')
            ->willReturn($spyPaymentRatepay);

        $spyPaymentRatepay->method('save')
            ->willReturn(true);

        return $spyPaymentRatepay;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface
     */
    protected function mockRatepayQueryContainer()
    {
        $queryContainer = $this->getMockBuilder(RatepayQueryContainerInterface::class)->getMock();
        $queryPaymentsMock = $this->getMockBuilder(SpyPaymentRatepayQuery::class)->setMethods(['findByFkSalesOrder', 'getFirst'])->getMock();

        $queryPaymentsMock->method('findByFkSalesOrder')->willReturnSelf();
        $queryPaymentsMock->method('getFirst')->willReturn($this->mockPaymentRatepay());
        $queryContainer->method('queryPayments')->willReturn($queryPaymentsMock);

        return $queryContainer;
    }

    /**
     * @param string $paymentMethod
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mockQuoteTransfer($paymentMethod = 'INVOICE')
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentMethod($paymentMethod);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPayment($paymentTransfer);

        $quoteTransfer
            ->setCustomer($this->mockCustomerTransfer())
            ->setBillingAddress($this->mockAddressTransfer())
            ->setShippingAddress($this->mockAddressTransfer());

        $total = new TotalsTransfer();
        $total->setGrandTotal(1800)
            ->setExpenseTotal(0);
        $quoteTransfer->setTotals($total);

        $quoteTransfer->addItem($this->mockItemTransfer());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mockItemTransfer()
    {
        $item = new ItemTransfer();
        $item
            ->setName('1test')
            ->setSku('133333')
            ->setAbstractSku('133333333333')
            ->setQuantity(3)
            ->setTaxRate(19)
            ->setUnitGrossPriceWithProductOptions(1000)
            ->setGroupKey('133333333333');
        return $item;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentInitTransfer
     */
    protected function mockRatepayPaymentInitTransfer()
    {
        $ratepayPaymentInitTransfer = new RatepayPaymentInitTransfer();
        $ratepayPaymentInitTransfer
            ->setTransactionId('58-201604122719694')
            ->setTransactionShortId('5QTZ.2VWD.OMWW.9D3E');

        return $ratepayPaymentInitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer|\Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer|null $paymentData
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentRequestTransfer
     */
    protected function mockRatepayPaymentRequestTransfer($paymentData = null)
    {
        if ($paymentData === null) {
            $paymentData = $this->mockPaymentElvTransfer();
        }

        $ratepayPaymentRequestTransfer = new RatepayPaymentRequestTransfer();
        $ratepayPaymentInitTransfer = $this->mockRatepayPaymentInitTransfer();
        $quotePaymentRequestMapper = new QuotePaymentRequestMapper(
            $ratepayPaymentRequestTransfer,
            $ratepayPaymentInitTransfer,
            $this->mockQuoteTransfer(),
            $this->mockPartialOrderTransfer(),
            $paymentData
        );
        $quotePaymentRequestMapper->map();
        $ratepayPaymentRequestTransfer->setDiscountTotal(200);

        return $ratepayPaymentRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function mockCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail("email@site.com");

        return $customerTransfer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\Invoice
     */
    protected function mockMethodInvoice()
    {
        $paymentInit = $this->mockModelPaymentInit();

        $paymentTransfer = new RatepayPaymentInvoiceTransfer();

        $invoiceMethod = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->getMock();

        $invoiceMethod->method('getMethodName')
            ->willReturn(RatepayConstants::INVOICE);

        $invoiceMethod->method('paymentInit')
            ->willReturn($paymentInit);

        $invoiceMethod->method('paymentRequest')
            ->willReturn($paymentInit);

        $invoiceMethod->method('paymentConfirm')
            ->willReturn($paymentInit);

        $invoiceMethod->method('paymentCancel')
            ->willReturn($paymentInit);

        $invoiceMethod->method('deliveryConfirm')
            ->willReturn($paymentInit);

        $invoiceMethod->method('paymentRefund')
            ->willReturn($paymentInit);

        $invoiceMethod->method('getPaymentData')
            ->willReturn($paymentTransfer);

        return $invoiceMethod;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\Installment
     */
    protected function mockMethodInstallmentConfiguration()
    {
        $paymentConfiguration = $this->mockModelPaymentConfiguration();

        return $this->mockMethodInstallment($paymentConfiguration);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\Installment
     */
    protected function mockMethodInstallmentCalculation()
    {
        $paymentCalculation = $this->mockModelPaymentCalculation();

        return $this->mockMethodInstallment($paymentCalculation);
    }

    /**
     * @param string $payment
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Ratepay\Business\Request\Payment\Method\Installment
     */
    protected function mockMethodInstallment($payment)
    {
        $paymentTransfer = new RatepayPaymentInvoiceTransfer();

        $installmentMethod = $this->getMockBuilder(Installment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $installmentMethod->method('getMethodName')
            ->willReturn(RatepayConstants::INSTALLMENT);

        $installmentMethod->method('paymentInit')
            ->willReturn($payment);

        $installmentMethod->method('paymentRequest')
            ->willReturn($payment);

        $installmentMethod->method('paymentConfirm')
            ->willReturn($payment);

        $installmentMethod->method('configurationRequest')
            ->willReturn($this->mockModelPaymentConfiguration());

        $installmentMethod->method('calculationRequest')
            ->willReturn($this->mockModelPaymentCalculation());

        $installmentMethod->method('getPaymentData')
            ->willReturn($paymentTransfer);

        return $installmentMethod;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    protected function mockModelPaymentRequest()
    {
        $modelPaymentRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $modelPaymentRequest->method('getHead')
            ->willReturn($this->mockModelPartHead());

        $modelPaymentRequest->method('getPayment')
            ->willReturn($this->mockModelPartPayment());

        return $modelPaymentRequest;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    protected function mockModelPaymentInit()
    {
        $paymentInit = $this->getMockBuilder(Init::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHead', 'getPayment'])
            ->getMock();

        $paymentInit->method('getHead')
            ->willReturn($this->mockModelPartHead());

        $paymentInit->method('getPayment')
            ->willReturn($this->mockModelPartPayment());

        return $paymentInit;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration
     */
    protected function mockModelPaymentConfiguration()
    {
        $paymentConfiguration = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->setMethods(['getHead', 'getPayment'])
            ->getMock();

        $paymentConfiguration->method('getHead')
            ->willReturn($this->mockModelPartHead());

        $paymentConfiguration->method('getPayment')
            ->willReturn($this->mockModelPartPayment());

        return $paymentConfiguration;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    protected function mockModelPaymentCalculation()
    {
        $paymentCalculation = new Calculation(
            $this->mockModelPartHead(),
            $this->mockModelPartInstallmentCalculation()
        );

        return $paymentCalculation;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\Head
     */
    protected function mockModelPartHead()
    {
        $this->mapperFactory
            ->getQuoteHeadMapper(
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer()
            )->map();

        $this->requestTransfer->getHead()->setOrderId(1)
            ->setOperation(Constants::REQUEST_MODEL_PAYMENT_REQUEST)
            ->setTransactionId('tr1')
            ->setTransactionShortId('tr1_short');

        return new Head($this->requestTransfer);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\Payment
     */
    protected function mockModelPartPayment()
    {
        $this->mapperFactory
            ->getPaymentMapper(
                $this->mockRatepayPaymentRequestTransfer()
            )->map();

        $this->requestTransfer->getPayment()->setMethod('');

        return new Payment($this->requestTransfer);
    }

    /**
     * @param string $subType
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\InstallmentCalculation
     */
    protected function mockModelPartInstallmentCalculation($subType = 'calculation_by_rate')
    {
        $this->mapperFactory
            ->getInstallmentCalculationMapper(
                $this->mockQuoteTransfer(),
                $this->mockRatepayPaymentInstallmentTransfer()
            )->map();

        $this->requestTransfer->getInstallmentCalculation()->setSubType($subType);

        return new InstallmentCalculation($this->requestTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function mockOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder(1);

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function mockPartialOrderTransfer()
    {
        $total = new TotalsTransfer();
        $total->setGrandTotal(1800)
            ->setExpenseTotal(0);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setTotals($total);

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function mockPaymentElvTransfer()
    {
        $ratepayPaymentTransfer = new RatepayPaymentElvTransfer();
        $ratepayPaymentTransfer->setBankAccountIban('iban')
            ->setBankAccountBic('bic')
            ->setBankAccountHolder('holder')
            ->setCurrencyIso3('iso3')
            ->setGender('m')
            ->setPhone('123456789')
            ->setDateOfBirth('1980-01-02')
            ->setIpAddress('127.1.2.3')
            ->setCustomerAllowCreditInquiry(true)
            ->setPaymentType('invoice');

        return $ratepayPaymentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function mockRatepayPaymentInstallmentTransfer()
    {
        $ratepayPaymentInstallmentTransfer = new RatepayPaymentInstallmentTransfer();
        $ratepayPaymentInstallmentTransfer
            ->setInstallmentCalculationType('calculation-by-rate')
            ->setInstallmentGrandTotalAmount(12570)
            ->setInstallmentRate(1200)
            ->setInstallmentInterestRate(14)
            ->setInstallmentLastRate(1450)
            ->setInstallmentMonth(3)
            ->setInterestRate(14)
            ->setInterestMonth(3)
            ->setInstallmentNumberRates(3)
            ->setInstallmentPaymentFirstDay(28)
            ->setInstallmentCalculationStart("2016-05-15")

            ->setBankAccountIban('iban')
            ->setBankAccountBic('bic')
            ->setBankAccountHolder('holder')
            ->setCurrencyIso3('iso3')
            ->setGender('m')
            ->setDateOfBirth('1980-01-02')
            ->setPhone('123456789')
            ->setIpAddress('127.1.2.3')
            ->setCustomerAllowCreditInquiry(true)
            ->setPaymentType('invoice')
            ->setDebitPayType('invoice');

        return $ratepayPaymentInstallmentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function mockAddressTransfer()
    {
        $address = new AddressTransfer();
        $address->setFirstName('fn')
            ->setLastName('ln')
            ->setPhone('0491234567')
            ->setCity('Berlin')
            ->setIso2Code('iso2')
            ->setAddress1('addr1')
            ->setAddress2('addr2')
            ->setZipCode('zip');

        return $address;
    }

    /**
     * @param string $className
     *
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\AbstractMethod
     */
    protected function mockPaymentMethod($className)
    {
        $paymentMethod = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->setMethods(['paymentInit', 'paymentRequest', 'configurationRequest', 'calculationRequest'])
            ->getMock();
        $paymentMethod->method('paymentInit')
            ->willReturn('');
        $paymentMethod->method('paymentRequest')
            ->willReturn('');
        $paymentMethod->method('configurationRequest')
            ->willReturn($this->mockModelPaymentConfiguration());
        $paymentMethod->method('calculationRequest')
            ->willReturn($this->mockModelPaymentCalculation());

        return $paymentMethod;
    }

}
