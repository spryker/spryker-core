<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DummyPayment\Business\Model\Payment;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\DummyPayment\Business\Model\Payment\Refund;
use Spryker\Zed\DummyPayment\Dependency\Facade\DummyPaymentToRefundInterface;

/**
 * @group Spryker
 * @group Zed
 * @group DummyPayment
 * @group Business
 * @group Refund
 */
class RefundTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSaveRefundShouldCalledWhenRefundProcessSuccessful()
    {
        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);
        $refundFacadeMock = $this->getRefundFacadeMock($refundTransfer);
        $refund = new Refund($refundFacadeMock);
        $refund->refund([], new SpySalesOrder());
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldNotCalledWhenRefundProcessNotSuccessful()
    {
        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(0);
        $refundFacadeMock = $this->getRefundFacadeMock($refundTransfer);
        $refund = new Refund($refundFacadeMock);
        $refund->refund([], new SpySalesOrder());
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DummyPayment\Dependency\Facade\DummyPaymentToRefundInterface
     */
    protected function getRefundFacadeMock(RefundTransfer $refundTransfer)
    {
        $refundFacadeMock = $this->getMock(DummyPaymentToRefundInterface::class);
        $refundFacadeMock->method('calculateRefund')->willReturn($refundTransfer);
        if ($refundTransfer->getAmount() > 0) {
            $refundFacadeMock->expects($this->once())->method('saveRefund');
        } else {
            $refundFacadeMock->expects($this->never())->method('saveRefund');
        }

        return $refundFacadeMock;
    }

}
