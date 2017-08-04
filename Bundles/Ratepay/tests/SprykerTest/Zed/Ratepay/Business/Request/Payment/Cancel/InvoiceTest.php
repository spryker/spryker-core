<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment\Cancel;

use SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\CancelAdapterMock;
use SprykerTest\Zed\Ratepay\Business\Request\Payment\InvoiceAbstractTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group Cancel
 * @group InvoiceTest
 * Add your own group annotations below this line
 */
class InvoiceTest extends InvoiceAbstractTest
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
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\CancelAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new CancelAdapterMock();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\CancelAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new CancelAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->cancelPayment($this->orderTransfer, $this->orderPartialTransfer, $this->orderTransfer->getItems()->getArrayCopy());
    }

}
