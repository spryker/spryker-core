<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\RestPaymentBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;

/**
 * Auto-generated group annotations
 *
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
        /** @var \Spryker\Zed\PaymentsRestApi\Business\PaymentsRestApiFacadeInterface $paymentsRestApiFacade */
        $paymentsRestApiFacade = $this->tester->getFacade();

        $restCheckoutRequestAttributesTransfer = $this->prepareRestCheckoutRequestAttributesTransferWithSinglePayment();
        $quoteTransfer = $this->prepareQuoteTransfer();

        $actualQuote = $paymentsRestApiFacade->mapPaymentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNotNull($actualQuote->getPayment());
        $this->assertCount(0, $actualQuote->getPayments());
    }

    /**
     * @return void
     */
    public function testPaymentRestApiFacadeWillMapNoPaymentsToQuote(): void
    {
        /** @var \Spryker\Zed\PaymentsRestApi\Business\PaymentsRestApiFacadeInterface $paymentsRestApiFacade */
        $paymentsRestApiFacade = $this->tester->getFacade();

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
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())
            ->withPayment($this->prepareRestPayment())
            ->build();

        return $restCheckoutRequestAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function prepareRestCheckoutRequestAttributesTransferWithoutPayments(): RestCheckoutRequestAttributesTransfer
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer */
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesBuilder())->build();

        return $restCheckoutRequestAttributesTransfer;
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
        ]));
    }
}
