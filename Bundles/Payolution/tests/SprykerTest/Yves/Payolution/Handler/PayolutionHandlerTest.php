<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Payolution\Handler;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Payolution\PayolutionClientInterface;
use Spryker\Yves\Payolution\Exception\PaymentMethodNotFoundException;
use Spryker\Yves\Payolution\Handler\PayolutionHandler;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Payolution
 * @group Handler
 * @group PayolutionHandlerTest
 * Add your own group annotations below this line
 */
class PayolutionHandlerTest extends Unit
{
    /**
     * @return void
     */
    public function testAddPaymentToQuoteShouldReturnQuoteTransfer()
    {
        $paymentHandler = new PayolutionHandler($this->getPayolutionClientMock());

        $request = Request::createFromGlobals();
        $quoteTransfer = new QuoteTransfer();

        $billingAddress = new AddressTransfer();
        $billingAddress->setSalutation('Mr');
        $billingAddress->setIso2Code('iso2Code');
        $quoteTransfer->setBillingAddress($billingAddress);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail('test@spryker.com');
        $quoteTransfer->setCustomer($customerTransfer);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection('payolutionInvoice');
        $payolutionPaymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer->setPayolutionInvoice($payolutionPaymentTransfer);
        $quoteTransfer->setPayment($paymentTransfer);

        $result = $paymentHandler->addPaymentToQuote($request, $quoteTransfer);
        $this->assertInstanceOf(QuoteTransfer::class, $result);
    }

    /**
     * @return void
     */
    public function testGetPayolutionPaymentTransferShouldThrowExceptionIfPaymentSelectionNotFound()
    {
        $paymentHandler = new PayolutionHandler($this->getPayolutionClientMock());

        $request = Request::createFromGlobals();
        $quoteTransfer = new QuoteTransfer();
        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection('payolutionInvoice');
        $quoteTransfer->setPayment($paymentTransfer);

        $this->expectException(PaymentMethodNotFoundException::class);

        $paymentHandler->addPaymentToQuote($request, $quoteTransfer);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Payolution\PayolutionClientInterface
     */
    private function getPayolutionClientMock()
    {
        return $this->getMockBuilder(PayolutionClientInterface::class)->getMock();
    }
}
