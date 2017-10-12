<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Order\MethodMapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Order
 * @group MethodMapper
 * @group BaseMethodMapperTest
 * Add your own group annotations below this line
 */
class BaseMethodMapperTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected $payment;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->quoteTransfer = $this->mockQuoteTransfer();
        $this->payment = new SpyPaymentRatepay();
        $this->payment->setBankAccountHolder('acchold');
    }

    /**
     * @return void
     */
    protected function testAbstractMapMethodDataToPayment()
    {
        $this->assertEquals($this->paymentMethod, $this->payment->getPaymentType());
        $this->assertEquals('tr1', $this->payment->getTransactionId());
        $this->assertEquals('trsh1', $this->payment->getTransactionShortId());
        $this->assertEquals('200', $this->payment->getResultCode());
        $this->assertEquals('fp', $this->payment->getDeviceFingerprint());
        $this->assertEquals('M', $this->payment->getGender());
        $this->assertEquals('1980-01-02', $this->payment->getDateOfBirth()->format('Y-m-d'));
        $this->assertEquals(1, $this->payment->getCustomerAllowCreditInquiry());
        $this->assertEquals('127.1.2.3', $this->payment->getIpAddress());
        $this->assertEquals('iso3', $this->payment->getCurrencyIso3());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mockQuoteTransfer()
    {
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentMethod($this->paymentMethod);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $paymentTransfer
     *
     * @return mixed
     */
    protected function mockPaymentTransfer($paymentTransfer)
    {
        $paymentTransfer->setTransactionId('tr1');
        $paymentTransfer->setTransactionShortId('trsh1');
        $paymentTransfer->setResultCode('200');
        $paymentTransfer->setDeviceFingerprint('fp');
        $paymentTransfer->setGender('M');
        $paymentTransfer->setPhone('123456789');
        $paymentTransfer->setDateOfBirth('1980-01-02');
        $paymentTransfer->setCustomerAllowCreditInquiry(1);
        $paymentTransfer->setIpAddress('127.1.2.3');
        $paymentTransfer->setCurrencyIso3('iso3');

        return $paymentTransfer;
    }
}
