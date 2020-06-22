<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OmsFacade;

use Codeception\Test\Unit;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OmsFacade
 * @group SetOrderIsCancellableByItemStateTest
 * Add your own group annotations below this line
 */
class SetOrderIsCancellableByItemStateTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS = 'Test05';
    protected const DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS = 'Test01';

    protected const SHIPPED_STATE_NAME = 'shipped';

    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([
            static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS,
            static::DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS,
        ]);
    }

    /**
     * @return void
     */
    public function testSetOrderIsCancellableByItemStateWithCancellableFLagsInOmsProcess(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        // Act
        $orderTransfers = $this->tester
            ->getFacade()
            ->setOrderIsCancellableByItemState([$orderTransfer]);

        // Assert
        $this->assertTrue(array_shift($orderTransfers)->getIsCancellable());
    }

    /**
     * @return void
     */
    public function testSetOrderIsCancellableByItemStateWithoutCancellableFLagsInOmsProcess(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS);

        // Act
        $orderTransfers = $this->tester
            ->getFacade()
            ->setOrderIsCancellableByItemState([$orderTransfer]);

        // Assert
        $this->assertFalse(array_shift($orderTransfers)->getIsCancellable());
    }

    /**
     * @return void
     */
    public function testSetOrderIsCancellableByItemStateWhenOneItemInStateWithoutCancellableFlag(): void
    {
        $this->skipIfMySql();

        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        $this->tester->setItemState(
            $orderTransfer->getItems()->getIterator()->current()->getIdSalesOrderItem(),
            static::SHIPPED_STATE_NAME
        );

        // Act
        $orderTransfers = $this->tester
            ->getFacade()
            ->setOrderIsCancellableByItemState([$orderTransfer]);

        // Assert
        $this->assertFalse(array_shift($orderTransfers)->getIsCancellable());
    }

    /**
     * @return void
     */
    public function testSetOrderIsCancellableByItemStateWhenAllItemsInStateWithoutCancellableFlag(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::SHIPPED_STATE_NAME);
        }

        // Act
        $orderTransfers = $this->tester
            ->getFacade()
            ->setOrderIsCancellableByItemState([$orderTransfer]);

        // Assert
        $this->assertFalse(array_shift($orderTransfers)->getIsCancellable());
    }

    /**
     * @return void
     */
    public function testSetOrderIsCancellableByItemStateWithTwoOrders(): void
    {
        // Arrange
        $firstOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITH_CANCELLABLE_FLAGS);
        $secondOrderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME_WITHOUT_CANCELLABLE_FLAGS);

        // Act
        $orderTransfers = $this->tester
            ->getFacade()
            ->setOrderIsCancellableByItemState([$firstOrderTransfer, $secondOrderTransfer]);

        // Assert
        $this->assertTrue($orderTransfers[0]->getIsCancellable());
        $this->assertFalse($orderTransfers[1]->getIsCancellable());
    }

    /**
     * @return void
     */
    protected function skipIfMySql(): void
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) === Config::get(PropelConstants::ZED_DB_ENGINE_MYSQL)) {
            $this->markTestSkipped('Not MYSQL related test');
        }
    }
}
