<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DummyPayment\Business;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\DummyPayment\Business\DummyPaymentBusinessFactory;
use Spryker\Zed\DummyPayment\Business\DummyPaymentFacade;
use Spryker\Zed\DummyPayment\Business\Model\Payment\RefundInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DummyPayment
 * @group Business
 * @group DummyPaymentFacadeTest
 */
class DummyPaymentFacadeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRefundShouldDelegateToRefundModel()
    {
        $dummyPaymentFactoryMock = $this->getFactoryMock();
        $dummyPaymentFacade = new DummyPaymentFacade();

        $dummyPaymentFacade->setFactory($dummyPaymentFactoryMock);

        $dummyPaymentFacade->refund([], new SpySalesOrder());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DummyPayment\Business\DummyPaymentBusinessFactory
     */
    protected function getFactoryMock()
    {
        $refundMock = $this->getMockBuilder(RefundInterface::class)->getMock();
        $refundMock->expects($this->once())->method('refund');

        $dummyPaymentFactoryMock = $this->getMockBuilder(DummyPaymentBusinessFactory::class)->getMock();
        $dummyPaymentFactoryMock->method('createRefund')->willReturn($refundMock);

        return $dummyPaymentFactoryMock;
    }

}
