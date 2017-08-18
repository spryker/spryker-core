<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment\Init;

use SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\InitAdapterMock;
use SprykerTest\Zed\Ratepay\Business\Request\Payment\ElvAbstractTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group Init
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
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\InitAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new InitAdapterMock();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\InitAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new InitAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->initPayment($this->mockRatepayPaymentInitTransfer());
    }

}
