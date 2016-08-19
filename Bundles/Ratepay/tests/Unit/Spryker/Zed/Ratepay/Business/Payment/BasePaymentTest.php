<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery;
use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Ratepay\Business\Api\Builder\Head;
use Spryker\Zed\Ratepay\Business\Api\Builder\InstallmentCalculation;
use Spryker\Zed\Ratepay\Business\Api\Builder\Payment;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Installment;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Invoice;
use Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface;
use Unit\Spryker\Zed\Ratepay\Business\Api\Response\Response;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Payment
 * @group BasePaymentTest
 */
class BasePaymentTest extends Test
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
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\QuoteTransactionInterface|\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\OrderTransactionInterface
     */
    protected function getTransactionHandlerObject($className, $additionalMockMethods = [])
    {
        $executionAdapter = $this->getMockBuilder(Guzzle::class)
            ->disableOriginalConstructor()
            ->setMethods(['sendRequest', 'getMethodMapper'])
            ->getMock();

        $executionAdapter->method('sendRequest')
            ->willReturn((new Response())->getTestPaymentConfirmResponseData());

        foreach ($additionalMockMethods as $method => $return) {
            $executionAdapter->method($method)
                ->willReturn($return);
        }

        $converterFactory = new ConverterFactory();

        $transactionHandler = $this->getMockBuilder($className)
            ->setConstructorArgs([
                $executionAdapter,
                $converterFactory,
                $this->mockRatepayQueryContainer()
            ])
            ->setMethods(['logInfo'])
            ->getMock();

        $transactionHandler->method('logInfo')
            ->willReturn(null);

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
        $queryContainer = $this->getMock(RatepayQueryContainerInterface::class);
        $queryPaymentsMock = $this->getMock(SpyPaymentRatepayQuery::class, ['findByFkSalesOrder', 'getFirst']);

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

        $quoteTransfer->setCustomer($this->mockCustomerTransfer());

        $total = new TotalsTransfer();
        $total->setGrandTotal(9900)
            ->setExpenseTotal(8900);
        $quoteTransfer->setTotals($total);

        return $quoteTransfer;
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Payolution\Business\Payment\Method\Installment\Installment
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
                $this->mockQuoteTransfer(),
                $this->mockPaymentElvTransfer()
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

}
