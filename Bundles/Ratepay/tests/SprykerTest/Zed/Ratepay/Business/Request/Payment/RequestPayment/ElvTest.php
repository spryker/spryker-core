<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment\RequestPayment;

use Spryker\Shared\Ratepay\RatepayConstants;
use SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentElvAdapterMock;
use SprykerTest\Zed\Ratepay\Business\Request\Payment\ElvAbstractTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group RequestPayment
 * @group ElvTest
 * Add your own group annotations below this line
 */
class ElvTest extends ElvAbstractTest
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
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentElvAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new RequestPaymentElvAdapterMock();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentElvAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new RequestPaymentElvAdapterMock())->expectFailure();
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

        $this->assertEquals(RatepayConstants::ELV, $this->responseTransfer->getPaymentMethod());
        $this->assertEquals($this->expectedResponseTransfer->getPaymentMethod(), $this->responseTransfer->getPaymentMethod());
    }

}
