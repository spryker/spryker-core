<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use Exception;
use Spryker\Zed\Oms\Business\Exception\LockException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group OrderStateMachine
 * @group LockedOrderStateMachineTest
 * Add your own group annotations below this line
 */
class LockedOrderStateMachineTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return string[][]
     */
    public function methodsUnderTestDataProvider(): array
    {
        return [
            'Trigger event' => ['triggerEvent'],
            'Trigger event for new item' => ['triggerEventForNewItem'],
            'Trigger event for new order items' => ['triggerEventForNewOrderItems'],
            'Trigger event for one order item' => ['triggerEventForOneOrderItem'],
            'Trigger event for order items' => ['triggerEventForOrderItems'],
        ];
    }

    /**
     * @dataProvider methodsUnderTestDataProvider()
     *
     * @param string $methodUnderTest
     *
     * @return void
     */
    public function testTriggerEventMethodWillAcquireSingleLockEntryForEachOrderItemAndReleasesLocksAfterProcessing(string $methodUnderTest): void
    {
        // Arrange
        $orderItemEntityCollection = $this->tester->createOrderItemEntityCollection();
        $lockedOrderStatemachine = $this->tester->createLockedOrderStatemachineWithTriggerSuccess();

        // Act
        $this->tester->callLockedOrderStatemachineMethod($methodUnderTest, $lockedOrderStatemachine, $orderItemEntityCollection);

        // Assert
        $this->assertFalse($this->tester->hasLockedOrderItems(), 'Expected all locks to be released but found lock entries.');
    }

    /**
     * @dataProvider methodsUnderTestDataProvider()
     *
     * @param string $methodUnderTest
     *
     * @return void
     */
    public function testTriggerEventMethodWithExceptionWillReleaseLocksBeforeExceptionIsForwarded(string $methodUnderTest): void
    {
        // Arrange
        $orderItemEntityCollection = $this->tester->createOrderItemEntityCollection();
        $lockedOrderStatemachine = $this->tester->createLockedOrderStatemachineWithTriggerException();

        // Expect
        $this->expectException(Exception::class);

        // Act
        $this->tester->callLockedOrderStatemachineMethod($methodUnderTest, $lockedOrderStatemachine, $orderItemEntityCollection);

        // Assert
        $this->assertFalse($this->tester->hasLockedOrderItems(), 'Expected all locks to be released but found lock entries.');
    }

    /**
     * @dataProvider methodsUnderTestDataProvider()
     *
     * @param string $methodUnderTest
     *
     * @return void
     */
    public function testTriggerEventMethodWithAlreadyLockedOrderItemsWillThrowAnException(string $methodUnderTest): void
    {
        // Arrange
        $orderItemEntityCollection = $this->tester->createOrderItemEntityCollection();
        $lockedOrderStatemachine = $this->tester->createLockedOrderStatemachineWithTriggerSuccess();
        $this->tester->lockOrderItems($orderItemEntityCollection);

        // Expect
        $this->expectException(LockException::class);

        // Act
        $this->tester->callLockedOrderStatemachineMethod($methodUnderTest, $lockedOrderStatemachine, $orderItemEntityCollection);

        // Assert
        $this->assertTrue($this->tester->hasLockedOrderItems(), 'Expected to have locks but all locks are released.');
    }

    /**
     * @return void
     */
    public function testCheckConditionMethodIsDecorated(): void
    {
        $lockedStateMachine = $this->tester->createLockedOrderStatemachineWithTriggerSuccess();
        $logContext = ['some log context'];

        $this->assertIsInt($lockedStateMachine->checkConditions($logContext));
    }
}
