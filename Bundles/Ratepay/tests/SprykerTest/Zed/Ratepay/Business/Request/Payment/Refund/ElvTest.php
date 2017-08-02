<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment\Refund;

use SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RefundAdapterMock;
use SprykerTest\Zed\Ratepay\Business\Request\Payment\ElvAbstractTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group Refund
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

        $this->setUpSalesOrderTestData();
        $this->setUpPaymentTestData();

        $this->orderTransfer->fromArray($this->orderEntity->toArray(), true);
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RefundAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new RefundAdapterMock();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\RefundAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new RefundAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->refundPayment($this->orderTransfer, $this->orderPartialTransfer, $this->orderTransfer->getItems()->getArrayCopy());
    }

}
