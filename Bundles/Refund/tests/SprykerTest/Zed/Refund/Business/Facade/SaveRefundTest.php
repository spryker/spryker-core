<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Refund\RefundDependencyProvider;
use Spryker\Zed\RefundExtension\Dependency\Plugin\RefundPostSavePluginInterface;
use SprykerTest\Zed\Refund\RefundBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Refund
 * @group Business
 * @group Facade
 * @group SaveRefundTest
 * Add your own group annotations below this line
 */
class SaveRefundTest extends Unit
{
    /**
     * @uses \SprykerTest\Zed\Sales\Helper\BusinessHelper::DEFAULT_OMS_PROCESS_NAME
     *
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\Refund\RefundBusinessTester
     */
    protected RefundBusinessTester $tester;

    /**
     * @return void
     */
    public function testSaveRefundShouldExecuteRefundPostSavePlugins(): void
    {
        // Arrange
        $this->tester->setDependency(
            RefundDependencyProvider::PLUGINS_REFUND_POST_SAVE,
            [$this->getRefundPostSavePluginMock()],
        );

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $saveOrderTransfer = $this->tester->haveOrder([ItemTransfer::UNIT_PRICE => 1000], static::DEFAULT_OMS_PROCESS_NAME);

        $refundTransfer = (new RefundTransfer())
            ->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setItems($saveOrderTransfer->getOrderItems())
            ->setAmount(15);

        // Act
        $this->tester->getFacade()->saveRefund($refundTransfer);
    }

    /**
     * @return \Spryker\Zed\RefundExtension\Dependency\Plugin\RefundPostSavePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRefundPostSavePluginMock(): RefundPostSavePluginInterface
    {
        $refundPostSavePluginMock = $this
            ->getMockBuilder(RefundPostSavePluginInterface::class)
            ->getMock();

        $refundPostSavePluginMock
            ->expects($this->once())
            ->method('postSave');

        return $refundPostSavePluginMock;
    }
}
