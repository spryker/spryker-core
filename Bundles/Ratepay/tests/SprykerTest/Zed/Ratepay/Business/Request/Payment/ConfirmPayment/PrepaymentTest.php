<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment\ConfirmPayment;

use SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\ConfirmPaymentAdapterMock;
use SprykerTest\Zed\Ratepay\Business\Request\Payment\PrepaymentAbstractTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group ConfirmPayment
 * @group PrepaymentTest
 * Add your own group annotations below this line
 */
class PrepaymentTest extends PrepaymentAbstractTest
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
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\ConfirmPaymentAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new ConfirmPaymentAdapterMock();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\ConfirmPaymentAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new ConfirmPaymentAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->confirmPayment($this->orderTransfer);
    }
}
