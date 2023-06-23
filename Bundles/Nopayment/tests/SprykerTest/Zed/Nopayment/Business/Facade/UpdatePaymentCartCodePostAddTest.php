<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Nopayment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\TotalsBuilder;
use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Nopayment\NopaymentConfig as SharedNopaymentConfig;
use SprykerTest\Zed\Nopayment\NopaymentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Nopayment
 * @group Business
 * @group Facade
 * @group UpdatePaymentCartCodePostAddTest
 * Add your own group annotations below this line
 */
class UpdatePaymentCartCodePostAddTest extends Unit
{
    /**
     * @var string
     */
    protected const DUMMY_PAYMENT_CREDIT_CARD = 'DummyPaymentCreditCard';

    /**
     * @var \SprykerTest\Zed\Nopayment\NopaymentBusinessTester
     */
    protected NopaymentBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldUpdatePaymentInQuoteWhileIsFullyPaid(): void
    {
        // Arrange
        $totalsTransfer = (new TotalsBuilder())->seed([
            TotalsTransfer::PRICE_TO_PAY => 0,
        ])->build();

        $paymentTransfer = (new PaymentBuilder())->seed([
            PaymentTransfer::PAYMENT_PROVIDER => static::DUMMY_PAYMENT_CREDIT_CARD,
            PaymentTransfer::PAYMENT_METHOD => static::DUMMY_PAYMENT_CREDIT_CARD,
            PaymentTransfer::PAYMENT_SELECTION => static::DUMMY_PAYMENT_CREDIT_CARD,
            PaymentTransfer::IS_LIMITED_AMOUNT => false,
        ])->build();

        $quoteTransfer = (new QuoteTransfer())->setPayment($paymentTransfer)->setTotals($totalsTransfer);
        $cartCodeRequestTransfer = (new CartCodeRequestTransfer())->setQuote($quoteTransfer);

        // Act
        /** @var \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer */
        $cartCodeResponseTransfer = $this->tester->getFacade()->updateCartCodeQuotePayment($cartCodeRequestTransfer);

        // Assert
        $this->assertTrue($cartCodeResponseTransfer->getIsSuccessfulOrFail());

        /** @var \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer */
        $paymentTransfer = $cartCodeResponseTransfer->getQuoteOrFail()->getPaymentOrFail();

        $this->assertSame(SharedNopaymentConfig::PAYMENT_PROVIDER_NAME, $paymentTransfer->getPaymentSelection());
        $this->assertSame(SharedNopaymentConfig::PAYMENT_PROVIDER_NAME, $paymentTransfer->getPaymentProvider());
        $this->assertSame(SharedNopaymentConfig::PAYMENT_METHOD_NAME, $paymentTransfer->getPaymentMethod());
        $this->assertTrue($paymentTransfer->getIsLimitedAmount());
        $this->assertSame(0, $paymentTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testShouldNotUpdatePaymentInQuoteWhileIsNotFullyPaid(): void
    {
        // Arrange
        $totalsTransfer = (new TotalsBuilder())->seed([
            TotalsTransfer::PRICE_TO_PAY => 1000,
        ])->build();

        $paymentTransfer = (new PaymentBuilder())->seed([
            PaymentTransfer::PAYMENT_PROVIDER => static::DUMMY_PAYMENT_CREDIT_CARD,
            PaymentTransfer::PAYMENT_METHOD => static::DUMMY_PAYMENT_CREDIT_CARD,
            PaymentTransfer::PAYMENT_SELECTION => static::DUMMY_PAYMENT_CREDIT_CARD,
            PaymentTransfer::IS_LIMITED_AMOUNT => false,
            PaymentTransfer::AMOUNT => 1000,
        ])->build();

        $quoteTransfer = (new QuoteTransfer())->setPayment($paymentTransfer)->setTotals($totalsTransfer);
        $cartCodeRequestTransfer = (new CartCodeRequestTransfer())->setQuote($quoteTransfer);

        // Act
        /** @var \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer */
        $cartCodeResponseTransfer = $this->tester->getFacade()->updateCartCodeQuotePayment($cartCodeRequestTransfer);

        // Assert
        $this->assertTrue($cartCodeResponseTransfer->getIsSuccessfulOrFail());

        /** @var \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer */
        $paymentTransfer = $cartCodeResponseTransfer->getQuoteOrFail()->getPaymentOrFail();

        $this->assertSame(static::DUMMY_PAYMENT_CREDIT_CARD, $paymentTransfer->getPaymentSelection());
        $this->assertSame(static::DUMMY_PAYMENT_CREDIT_CARD, $paymentTransfer->getPaymentProvider());
        $this->assertSame(static::DUMMY_PAYMENT_CREDIT_CARD, $paymentTransfer->getPaymentMethod());
        $this->assertFalse($paymentTransfer->getIsLimitedAmount());
        $this->assertSame(1000, $paymentTransfer->getAmount());
    }
}
