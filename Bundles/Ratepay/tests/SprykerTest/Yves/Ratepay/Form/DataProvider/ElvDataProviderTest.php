<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Yves\Ratepay\Form\DataProvider\ElvDataProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Ratepay
 * @group Form
 * @group DataProvider
 * @group ElvDataProviderTest
 * Add your own group annotations below this line
 */
class ElvDataProviderTest extends AbstractDataProviderTest
{
    /**
     * @return void
     */
    public function testGetDataShouldAddTransferIfNotSet()
    {
        $elvDataProvider = new ElvDataProvider();
        $quoteTransfer = $this->getQuoteTransfer();

        $elvDataProvider->getData($quoteTransfer);

        $paymentTransfer = $quoteTransfer->getPayment();
        $this->assertInstanceOf(PaymentTransfer::class, $paymentTransfer);
        $this->assertInstanceOf(RatepayPaymentElvTransfer::class, $paymentTransfer->getRatepayElv());
    }

    /**
     * @return void
     */
    public function testGetDataShouldAddPhoneNumber()
    {
        $elvDataProvider = new ElvDataProvider();
        $quoteTransfer = $this->getQuoteTransfer();

        $elvDataProvider->getData($quoteTransfer);

        $paymentTransfer = $quoteTransfer->getPayment();
        $this->assertInstanceOf(PaymentTransfer::class, $paymentTransfer);

        $paymentMethodTransfer = $paymentTransfer->getRatepayElv();
        $this->assertInstanceOf(RatepayPaymentElvTransfer::class, $paymentMethodTransfer);
        $this->assertSame(static::PHONE_NUMBER, $paymentMethodTransfer->getPhone());
    }
}
