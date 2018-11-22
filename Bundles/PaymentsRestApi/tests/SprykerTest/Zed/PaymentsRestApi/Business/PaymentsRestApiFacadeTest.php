<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCartBuilder;
use Generated\Shared\DataBuilder\RestPaymentBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PaymentsRestApi
 * @group Business
 * @group Facade
 * @group PaymentsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class PaymentsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PaymentsRestApi\PaymentsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPaymentRestApiFacadeWillMapSinglePaymentToQuote(): void
    {
        $paymentsRestApiFacade = $this->tester->getLocator()->paymentsRestApi()->facade();

        $restCheckoutRequestAttributesTransfer = $this->prepareRestCheckoutRequestAttributesTransferWithSinglePayment();
        $quoteTransfer = $this->prepareQuoteTransfer();

        $actualQuote = $paymentsRestApiFacade->mapPaymentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNotNull($actualQuote->getPayment());
        $unlimitedPayment = $restCheckoutRequestAttributesTransfer->getCart()->getPayments()->offsetGet(0);
        $this->assertEquals($unlimitedPayment->getPaymentProvider(), $actualQuote->getPayment()->getPaymentProvider());
        $this->assertEquals($unlimitedPayment->getPaymentSelection(), $actualQuote->getPayment()->getPaymentSelection());
        $this->assertEquals($unlimitedPayment->getPaymentMethod(), $actualQuote->getPayment()->getPaymentMethod());
        $this->assertEquals($unlimitedPayment->getIsLimitedAmount(), $actualQuote->getPayment()->getIsLimitedAmount());
    }

    /**
     * @return void
     */
    public function testPaymentRestApiFacadeWillMapSinglePaymentWithLimitedAmountToQuote(): void
    {
        $paymentsRestApiFacade = $this->tester->getLocator()->paymentsRestApi()->facade();

        $restCheckoutRequestAttributesTransfer = $this->prepareRestCheckoutRequestAttributesTransferWithSinglePaymentWithLimitedAmount();
        $quoteTransfer = $this->prepareQuoteTransfer();

        $actualQuote = $paymentsRestApiFacade->mapPaymentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getPayment());
        $this->assertCount(0, $actualQuote->getPayments());
    }

    /**
     * @return void
     */
    public function testPaymentRestApiFacadeWillMapMultiplePaymentsToQuote(): void
    {
        $paymentsRestApiFacade = $this->tester->getLocator()->paymentsRestApi()->facade();

        $restCheckoutRequestAttributesTransfer = $this->prepareRestCheckoutRequestAttributesTransferWithMultiplePayments();
        $quoteTransfer = $this->prepareQuoteTransfer();

        $actualQuote = $paymentsRestApiFacade->mapPaymentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNotNull($actualQuote->getPayment());
        $unlimitedPayment = $restCheckoutRequestAttributesTransfer->getCart()->getPayments()->offsetGet(0);
        $this->assertEquals($unlimitedPayment->getPaymentProvider(), $actualQuote->getPayment()->getPaymentProvider());
        $this->assertEquals($unlimitedPayment->getPaymentSelection(), $actualQuote->getPayment()->getPaymentSelection());
        $this->assertEquals($unlimitedPayment->getPaymentMethod(), $actualQuote->getPayment()->getPaymentMethod());
        $this->assertEquals($unlimitedPayment->getIsLimitedAmount(), $actualQuote->getPayment()->getIsLimitedAmount());
        $limitedPayment = $restCheckoutRequestAttributesTransfer->getCart()->getPayments()->offsetGet(1);
        $actualLimitedPayment = $actualQuote->getPayments()->offsetGet(0);
        $this->assertEquals($limitedPayment->getPaymentProvider(), $actualLimitedPayment->getPaymentProvider());
        $this->assertEquals($limitedPayment->getPaymentSelection(), $actualLimitedPayment->getPaymentSelection());
        $this->assertEquals($limitedPayment->getPaymentMethod(), $actualLimitedPayment->getPaymentMethod());
        $this->assertEquals($limitedPayment->getIsLimitedAmount(), $actualLimitedPayment->getIsLimitedAmount());
    }

    /**
     * @return void
     */
    public function testPaymentRestApiFacadeWillMapNoPaymentsToQuote(): void
    {
        $paymentsRestApiFacade = $this->tester->getLocator()->paymentsRestApi()->facade();

        $restCheckoutRequestAttributesTransfer = $this->prepareRestCheckoutRequestAttributesTransferWithoutPayments();
        $quoteTransfer = $this->prepareQuoteTransfer();

        $actualQuote = $paymentsRestApiFacade->mapPaymentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getPayment());
        $this->assertCount(0, $actualQuote->getPayments());
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function prepareRestCheckoutRequestAttributesTransferWithSinglePayment(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCartTransfer $restCart */
        $restCart = (new RestCartBuilder())
            ->withPayment($this->prepareRestPayment())
            ->build();

        return (new RestCheckoutRequestAttributesTransfer())->setCart($restCart);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function prepareRestCheckoutRequestAttributesTransferWithSinglePaymentWithLimitedAmount(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCartTransfer $restCart */
        $restCart = (new RestCartBuilder())
            ->withPayment($this->prepareRestPaymentWithLimitedAmount())
            ->build();

        return (new RestCheckoutRequestAttributesTransfer())->setCart($restCart);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function prepareRestCheckoutRequestAttributesTransferWithMultiplePayments(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCartTransfer $restCart */
        $restCart = (new RestCartBuilder())
            ->withPayment($this->prepareRestPayment())
            ->withAnotherPayment($this->prepareRestPaymentWithLimitedAmount())
            ->build();

        return (new RestCheckoutRequestAttributesTransfer())->setCart($restCart);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function prepareRestCheckoutRequestAttributesTransferWithoutPayments(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCartTransfer $restCart */
        $restCart = (new RestCartBuilder())->build();

        return (new RestCheckoutRequestAttributesTransfer())->setCart($restCart);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function prepareQuoteTransfer(): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())->build();

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\DataBuilder\RestPaymentBuilder
     */
    protected function prepareRestPayment(): RestPaymentBuilder
    {
        return (new RestPaymentBuilder([
            'paymentProvider' => 'dummyPayment',
            'paymentMethod' => 'invoice',
            'paymentSelection' => 'dummyPaymentInvoice',
            'isLimitedAmount' => false,
        ]))
            ->withDummyPayment()
            ->withDummyPaymentInvoice();
    }

    /**
     * @return \Generated\Shared\DataBuilder\RestPaymentBuilder
     */
    protected function prepareRestPaymentWithLimitedAmount(): RestPaymentBuilder
    {
        return (new RestPaymentBuilder([
            'paymentProvider' => 'dummyPayment',
            'paymentMethod' => 'creditCard',
            'paymentSelection' => 'dummyPaymentCreditCard',
            'isLimitedAmount' => true,
        ]))
            ->withDummyPayment()
            ->withDummyPaymentCreditCard();
    }
}
