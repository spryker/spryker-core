<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Yves\Ratepay\Form\DataProvider\ElvDataProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Ratepay
 * @group Form
 * @group DataProvider
 * @group ElvDataProviderTest
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
