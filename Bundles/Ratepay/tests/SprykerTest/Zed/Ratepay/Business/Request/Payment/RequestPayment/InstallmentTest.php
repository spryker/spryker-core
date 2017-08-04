<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment\RequestPayment;

use Spryker\Shared\Ratepay\RatepayConstants;
use SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentInstallmentAdapterMock;
use SprykerTest\Zed\Ratepay\Business\Request\Payment\InstallmentAbstractTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group RequestPayment
 * @group InstallmentTest
 * Add your own group annotations below this line
 */
class InstallmentTest extends InstallmentAbstractTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->quoteTransfer = $this->getQuoteTransfer();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentInstallmentAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new RequestPaymentInstallmentAdapterMock();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentInstallmentAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new RequestPaymentInstallmentAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->requestPayment($this->mockRatepayPaymentRequestTransfer());
    }

    /**
     * @return void
     */
    public function testPaymentWithSuccessResponse()
    {
        parent::testPaymentWithSuccessResponse();

        $this->assertEquals(RatepayConstants::INSTALLMENT, $this->responseTransfer->getPaymentMethod());
        $this->assertEquals($this->expectedResponseTransfer->getPaymentMethod(), $this->responseTransfer->getPaymentMethod());
    }

}
