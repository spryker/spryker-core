<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Business;

use Codeception\Test\Unit;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\DummyPayment\Business\DummyPaymentBusinessFactory;
use Spryker\Zed\DummyPayment\Business\DummyPaymentFacade;
use Spryker\Zed\DummyPayment\Business\Model\Payment\RefundInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Business
 * @group Facade
 * @group DummyPaymentFacadeTest
 * Add your own group annotations below this line
 */
class DummyPaymentFacadeTest extends Unit
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DummyPayment\Business\DummyPaymentBusinessFactory
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
