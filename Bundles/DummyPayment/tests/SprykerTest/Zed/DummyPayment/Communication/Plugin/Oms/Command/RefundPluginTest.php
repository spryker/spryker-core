<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Communication\Plugin\Oms\Command;

use Codeception\Test\Unit;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\DummyPayment\Business\DummyPaymentFacade;
use Spryker\Zed\DummyPayment\Communication\Plugin\Oms\Command\RefundPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group Command
 * @group RefundPluginTest
 * Add your own group annotations below this line
 */
class RefundPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testRunShouldDelegateToFacade()
    {
        $dummyPaymentFacadeMock = $this->getDummyPaymentFacadeMock();
        $refundPlugin = new RefundPlugin();
        $refundPlugin->setFacade($dummyPaymentFacadeMock);

        $refundPlugin->run([], new SpySalesOrder(), new ReadOnlyArrayObject());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DummyPayment\Business\DummyPaymentFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getDummyPaymentFacadeMock()
    {
        $dummyPaymentFacadeMock = $this->getMockBuilder(DummyPaymentFacade::class)->getMock();
        $dummyPaymentFacadeMock->expects($this->once())->method('refund');

        return $dummyPaymentFacadeMock;
    }
}
