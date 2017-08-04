<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Spryker\Yves\Ratepay\Form\DataProvider\InvoiceDataProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Ratepay
 * @group Form
 * @group DataProvider
 * @group InvoiceDataProviderTest
 * Add your own group annotations below this line
 */
class InvoiceDataProviderTest extends AbstractDataProviderTest
{

    /**
     * @return void
     */
    public function testGetDataShouldAddTransferIfNotSet()
    {
        $invoiceDataProvider = $this->getInvoiceDataProvider();
        $quoteTransfer = $this->getQuoteTransfer();

        $invoiceDataProvider->getData($quoteTransfer);

        $paymentTransfer = $quoteTransfer->getPayment();
        $this->assertInstanceOf(PaymentTransfer::class, $paymentTransfer);
        $this->assertInstanceOf(RatepayPaymentInvoiceTransfer::class, $paymentTransfer->getRatepayInvoice());
    }

    /**
     * @return void
     */
    public function testGetDataShouldAddPhoneNumber()
    {
        $invoiceDataProvider = $this->getInvoiceDataProvider();

        $quoteTransfer = $this->getQuoteTransfer();

        $invoiceDataProvider->getData($quoteTransfer);

        $paymentTransfer = $quoteTransfer->getPayment();
        $this->assertInstanceOf(PaymentTransfer::class, $paymentTransfer);

        $paymentMethodTransfer = $paymentTransfer->getRatepayInvoice();
        $this->assertInstanceOf(RatepayPaymentInvoiceTransfer::class, $paymentMethodTransfer);
        $this->assertSame(static::PHONE_NUMBER, $paymentMethodTransfer->getPhone());
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\InstallmentDataProvider
     */
    protected function getInvoiceDataProvider()
    {
        $invoiceDataProvider = new InvoiceDataProvider();

        return $invoiceDataProvider;
    }

}
