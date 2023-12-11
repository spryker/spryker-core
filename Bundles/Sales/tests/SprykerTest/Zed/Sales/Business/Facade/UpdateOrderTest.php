<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\Test\Unit;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostUpdatePluginInterface;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group UpdateOrderTest
 * Add your own group annotations below this line
 */
class UpdateOrderTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testExecutesOrderPostUpdatePlugins(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);

        $orderPostUpdatePluginMock = $this->getMockBuilder(OrderPostUpdatePluginInterface::class)->getMock();
        $this->tester->setDependency(SalesDependencyProvider::PLUGINS_ORDER_POST_UPDATE, [$orderPostUpdatePluginMock]);

        // Assert
        $orderPostUpdatePluginMock
            ->expects($this->once())
            ->method('execute');

        // Act
        $this->tester->getFacade()->updateOrder($orderTransfer, $orderTransfer->getIdSalesOrder());
    }
}
