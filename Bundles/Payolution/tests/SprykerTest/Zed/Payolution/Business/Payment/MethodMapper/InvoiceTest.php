<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payolution\Business\Payment\MethodMapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Zed\Money\Business\MoneyFacade;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\Business\Payment\Method\Invoice\Invoice;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyBridge;
use Spryker\Zed\Payolution\PayolutionConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payolution
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group InvoiceTest
 * Add your own group annotations below this line
 */
class InvoiceTest extends Unit
{
    /**
     * @return void
     */
    public function testMapToPreCheck()
    {
        $quoteTransfer = $this->getQuoteTransfer();
        $methodMapper = new Invoice($this->getBundleConfigMock(), $this->getMoneyFacade());

        $requestData = $methodMapper->buildPreCheckRequest($quoteTransfer);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_PRE_CHECK, $requestData['PAYMENT.CODE']);
        $this->assertSame('Straße des 17. Juni 135', $requestData['ADDRESS.STREET']);
        $this->assertSame(ApiConstants::CRITERION_PRE_CHECK, 'CRITERION.PAYOLUTION_PRE_CHECK');
        $this->assertSame('TRUE', $requestData['CRITERION.PAYOLUTION_PRE_CHECK']);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function getQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(10000)
            ->setSubtotal(10000);

        $quoteTransfer->setTotals($totalsTransfer);

        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setCity('Berlin')
            ->setIso2Code('DE')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623');

        $quoteTransfer->setBillingAddress($addressTransfer);

        $paymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer
            ->setGender('Male')
            ->setDateOfBirth('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setAddress($addressTransfer);

        $payment = new PaymentTransfer();
        $payment->setPayolution($paymentTransfer);
        $quoteTransfer->setPayment($payment);

        return $quoteTransfer;
    }

    /**
     * @return void
     */
    public function testMapToPreAuthorization()
    {
        $methodMapper = new Invoice($this->getBundleConfigMock(), $this->getMoneyFacade());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $orderTransfer = $this->createOrderTransfer();
        $requestData = $methodMapper->buildPreAuthorizationRequest($orderTransfer, $paymentEntityMock);

        $this->assertSame($paymentEntityMock->getEmail(), $requestData['CONTACT.EMAIL']);
        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION, $requestData['PAYMENT.CODE']);
    }

    /**
     * @return void
     */
    public function testMapToReAuthorization()
    {
        $uniqueId = $this->getRandomString();
        $methodMapper = new Invoice($this->getBundleConfigMock(), $this->getMoneyFacade());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $orderTransfer = $this->createOrderTransfer();
        $requestData = $methodMapper->buildReAuthorizationRequest($orderTransfer, $paymentEntityMock, $uniqueId);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_RE_AUTHORIZATION, $requestData['PAYMENT.CODE']);
        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
    }

    /**
     * @return string
     */
    private function getRandomString()
    {
        $utilTextService = new UtilTextService();

        return 'test_' . $utilTextService->generateRandomString(32);
    }

    /**
     * @return void
     */
    public function testMapToReversal()
    {
        $uniqueId = $this->getRandomString();
        $methodMapper = new Invoice($this->getBundleConfigMock(), $this->getMoneyFacade());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $orderTransfer = $this->createOrderTransfer();
        $requestData = $methodMapper->buildRevertRequest($orderTransfer, $paymentEntityMock, $uniqueId);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_REVERSAL, $requestData['PAYMENT.CODE']);
        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
    }

    /**
     * @return void
     */
    public function testMapToCapture()
    {
        $uniqueId = $this->getRandomString();
        $methodMapper = new Invoice($this->getBundleConfigMock(), $this->getMoneyFacade());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $orderTransfer = $this->createOrderTransfer();
        $requestData = $methodMapper->buildCaptureRequest($orderTransfer, $paymentEntityMock, $uniqueId);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_CAPTURE, $requestData['PAYMENT.CODE']);
        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
    }

    /**
     * @return void
     */
    public function testMapToRefund()
    {
        $uniqueId = $this->getRandomString();
        $methodMapper = new Invoice($this->getBundleConfigMock(), $this->getMoneyFacade());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $orderTransfer = $this->createOrderTransfer();
        $requestData = $methodMapper->buildRefundRequest($orderTransfer, $paymentEntityMock, $uniqueId);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_REFUND, $requestData['PAYMENT.CODE']);
        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();
        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setGrandTotal(1000);
        $orderTransfer->setTotals($totalTransfer);

        return $orderTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Payolution\PayolutionConfig
     */
    private function getBundleConfigMock()
    {
        return $this->getMockBuilder(PayolutionConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    private function getPaymentEntityMock()
    {
        $orderEntityMock = $this->getMockBuilder(SpySalesOrder::class)->getMock();

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution|\PHPUnit_Framework_MockObject_MockObject $paymentEntityMock */
        $paymentEntityMock = $this->getMockBuilder(SpyPaymentPayolution::class)
            ->setMethods([
                'getSpySalesOrder',
            ])
            ->getMock();

        $paymentEntityMock
            ->expects($this->any())
            ->method('getSpySalesOrder')
            ->will($this->returnValue($orderEntityMock));

        $paymentEntityMock
            ->setIdPaymentPayolution(1)
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com')
            ->setSalutation('Mr')
            ->setDateOfBirth('1970-01-01')
            ->setCountryIso2Code('DE')
            ->setCity('Berlin')
            ->setStreet('Straße des 17. Juni 135')
            ->setZipCode('10623')
            ->setGender(SpyPaymentPayolutionTableMap::COL_GENDER_FEMALE);

        return $paymentEntityMock;
    }

    /**
     * @return \Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        $payolutionToMoneyBridge = new PayolutionToMoneyBridge(new MoneyFacade());

        return $payolutionToMoneyBridge;
    }
}
