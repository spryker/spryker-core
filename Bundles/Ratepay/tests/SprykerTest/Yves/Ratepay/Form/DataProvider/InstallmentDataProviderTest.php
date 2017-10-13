<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Client\Ratepay\RatepayClientInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Yves\Ratepay\Form\DataProvider\InstallmentDataProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Ratepay
 * @group Form
 * @group DataProvider
 * @group InstallmentDataProviderTest
 * Add your own group annotations below this line
 */
class InstallmentDataProviderTest extends AbstractDataProviderTest
{
    /**
     * @return void
     */
    public function testGetDataShouldAddTransferIfNotSet()
    {
        $installmentDataProvider = $this->getInstallmentDataProvider();
        $quoteTransfer = $this->getQuoteTransfer();

        $installmentDataProvider->getData($quoteTransfer);

        $paymentTransfer = $quoteTransfer->getPayment();
        $this->assertInstanceOf(PaymentTransfer::class, $paymentTransfer);
        $this->assertInstanceOf(RatepayPaymentInstallmentTransfer::class, $paymentTransfer->getRatepayInstallment());
    }

    /**
     * @return void
     */
    public function testGetDataShouldAddPhoneNumber()
    {
        $installmentDataProvider = $this->getInstallmentDataProvider();

        $quoteTransfer = $this->getQuoteTransfer();

        $installmentDataProvider->getData($quoteTransfer);

        $paymentTransfer = $quoteTransfer->getPayment();
        $this->assertInstanceOf(PaymentTransfer::class, $paymentTransfer);

        $paymentMethodTransfer = $paymentTransfer->getRatepayInstallment();
        $this->assertInstanceOf(RatepayPaymentInstallmentTransfer::class, $paymentMethodTransfer);
        $this->assertSame(static::PHONE_NUMBER, $paymentMethodTransfer->getPhone());
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\InstallmentDataProvider
     */
    protected function getInstallmentDataProvider()
    {
        $installmentDataProvider = new InstallmentDataProvider($this->getRatepayClientMock(), $this->getSessionClientMock());

        return $installmentDataProvider;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Ratepay\RatepayClientInterface
     */
    protected function getRatepayClientMock()
    {
        return $this->getMockBuilder(RatepayClientInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClientMock()
    {
        return $this->getMockBuilder(SessionClientInterface::class)->getMock();
    }
}
