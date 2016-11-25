<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Ratepay\Business\Request\Payment\RequestPayment;

use Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentElvAdapterMock;
use Functional\Spryker\Zed\Ratepay\Business\Request\Payment\ElvAbstractTest;
use Spryker\Shared\Ratepay\RatepayConstants;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group RequestPayment
 * @group ElvTest
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
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentElvAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new RequestPaymentElvAdapterMock();
    }

    /**
     * @return \Functional\Spryker\Zed\Ratepay\Business\Api\Adapter\Http\RequestPaymentElvAdapterMock
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
