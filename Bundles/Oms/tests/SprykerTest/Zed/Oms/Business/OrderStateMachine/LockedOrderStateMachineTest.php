<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\OrderStateMachine;

use Codeception\Test\Unit;
use DateTime;
use Exception;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Exception\PropelException;
use ReflectionMethod;
use Spryker\Zed\Oms\Business\Exception\LockException;
use Spryker\Zed\Oms\Business\Lock\TriggerLocker;
use Spryker\Zed\Oms\Business\OrderStateMachine\LockedOrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainer;

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
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $stateMachineMock;

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\LockedOrderStateMachine
     */
    protected $lockedStateMachine;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $omsQueryContainerMock;

    /**
     * @var \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $omsQueryMock;

    /**
     * @var \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $omsStateMachineLockMock;

    /**
     * @var \Spryker\Zed\Oms\Business\Lock\TriggerLocker|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $triggerLockerMock;

    /**
     * Because LockedStateMachine contains 5 similar methods (because it is a decorator), which are just calling
     * different StateMachine methods, this data provider is used to test similar cases for all these methods,
     * reducing the amount of code 5 times
     *
     * @return array
     */
    public function triggerEventsDataProvider(): array
    {
        $eventId = 'eventId';
        $orderItems = $this->createOrderItems();
        $orderItemIds = [10, 11, 12];
        $orderItemsIdentifier = $this->hashIdentifier('10-11-12');
        $singleOrderItemIdentifier = $this->hashIdentifier('10');

        return [
            ['triggerEvent', $orderItemsIdentifier, $eventId, $orderItems, []],
            ['triggerEventForNewItem', $orderItemsIdentifier,  $orderItems, []],
            ['triggerEventForNewOrderItems', $orderItemsIdentifier, $orderItemIds, []],
            ['triggerEventForOneOrderItem', $singleOrderItemIdentifier, $eventId, $orderItemIds[0], []],
            ['triggerEventForOrderItems', $orderItemsIdentifier, $eventId, $orderItemIds, []],
        ];
    }

    /**
     * @dataProvider triggerEventsDataProvider
     *
     * @return void
     */
    public function testTriggerSimilarEventsWhenTriggerIsLocked(): void
    {
        $this->expectException(LockException::class);
        $arguments = func_get_args();
        $methodToTest = array_shift($arguments);
        $expectedIdentifier = array_shift($arguments);

        $lockedStateMachine = $this->createLockedStateMachine();
        $this->expectStateMachineLockSaveFails($expectedIdentifier);

        call_user_func_array([$lockedStateMachine, $methodToTest], $arguments);
    }

    /**
     * @dataProvider triggerEventsDataProvider
     *
     * @return void
     */
    public function testTriggerSimilarEventsLockReleasesWhenTriggerSuccess(): void
    {
        $arguments = func_get_args();
        $methodToTest = array_shift($arguments);
        $expectedIdentifier = array_shift($arguments);

        $lockedStateMachine = $this->createLockedStateMachine();

        $this->expectStateMachineLockSaveSuccess($expectedIdentifier);
        $this->expectTriggerRelease($expectedIdentifier);

        call_user_func_array([$lockedStateMachine, $methodToTest], $arguments);
    }

    /**
     * @dataProvider triggerEventsDataProvider
     *
     * @return void
     */
    public function testTriggerEventLockReleasesWhenTriggerFails(): void
    {
        $this->expectException(Exception::class);
        $arguments = func_get_args();
        $methodToTest = array_shift($arguments);
        $expectedIdentifier = array_shift($arguments);

        $lockedStateMachine = $this->createLockedStateMachine();

        $this->expectStateMachineLockSaveSuccess($expectedIdentifier);

        $this->stateMachineMock->expects($this->once())
            ->method($methodToTest)
            ->willThrowException(new Exception('Something bad happened'));

        $this->expectTriggerRelease($expectedIdentifier);

        call_user_func_array([$lockedStateMachine, $methodToTest], $arguments);
    }

    /**
     * @return void
     */
    public function testCheckConditionMethodIsDecorated(): void
    {
        $lockedStateMachine = $this->createLockedStateMachine();
        $logContext = ['some log context'];

        $this->stateMachineMock->expects($this->once())
            ->method('checkConditions')
            ->with($logContext);

        $lockedStateMachine->checkConditions($logContext);
    }

    /**
     * @return void
     */
    public function testIdentifierGeneratedSameForOrderIdsDifferentOrderAndNotUnique(): void
    {
        $testIdsList1 = ['100', '11', '12', '10', 11, 12];
        $testIdsList2 = [12, 11, 100, '10'];

        $expectedResult = $this->hashIdentifier('10-11-12-100');

        $lockedStateMachine = $this->createLockedStateMachine();

        $generateIdentifierMethod = new ReflectionMethod($lockedStateMachine, 'buildIdentifierForOrderItemIdsLock');
        $generateIdentifierMethod->setAccessible(true);

        $this->assertEquals($expectedResult, $generateIdentifierMethod->invoke($lockedStateMachine, $testIdsList1));
        $this->assertEquals($expectedResult, $generateIdentifierMethod->invoke($lockedStateMachine, $testIdsList2));
    }

    /**
     * @param string $identifer
     *
     * @return string
     */
    protected function hashIdentifier(string $identifer): string
    {
        return hash('sha512', $identifer);
    }

    /**
     * @param string $expectedIdentifier
     *
     * @return void
     */
    protected function expectStateMachineLockSaveSuccess(string $expectedIdentifier): void
    {
        $stateMachineLock = $this->createOmsStateMachineLockEntityMock();

        $stateMachineLock->expects($this->once())
            ->method('setIdentifier')
            ->with($expectedIdentifier);

        $stateMachineLock->expects($this->once())
            ->method('setExpires')
            ->with($this->isInstanceOf(DateTime::class));

        $stateMachineLock->expects($this->once())
            ->method('save')
            ->willReturn(1);

        $this->triggerLockerMock->expects($this->once())
            ->method('createStateMachineLockEntity')
            ->willReturn($stateMachineLock);
    }

    /**
     * @param string $expectedIdentifier
     *
     * @return void
     */
    protected function expectStateMachineLockSaveFails(string $expectedIdentifier): void
    {
        $stateMachineLock = $this->createOmsStateMachineLockEntityMock();

        $stateMachineLock->expects($this->once())
            ->method('setIdentifier')
            ->with($expectedIdentifier);

        $stateMachineLock->expects($this->once())
            ->method('setExpires')
            ->with($this->isInstanceOf(DateTime::class));

        $stateMachineLock->expects($this->once())
            ->method('save')
            ->willThrowException(new PropelException());

        $this->triggerLockerMock->expects($this->once())
            ->method('createStateMachineLockEntity')
            ->willReturn($stateMachineLock);
    }

    /**
     * @param string $identifier
     *
     * @return void
     */
    protected function expectTriggerRelease(string $identifier): void
    {
        $queryMock = $this->createOmsQueryMock();
        $queryMock->expects($this->once())
            ->method('delete');

        $this->omsQueryContainerMock->expects($this->once())
            ->method('queryLockItemsByIdentifier')
            ->with($identifier)
            ->willReturn($queryMock);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\LockedOrderStateMachine
     */
    protected function createLockedStateMachine(): LockedOrderStateMachine
    {
        return new LockedOrderStateMachine(
            $this->createStateMachineMock(),
            $this->createTriggerLockerMock()
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStateMachineMock()
    {
        $this->stateMachineMock = $this->getMockForAbstractClass(
            OrderStateMachineInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['triggerEvent']
        );

        return $this->stateMachineMock;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Lock\TriggerLocker|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createTriggerLockerMock()
    {
        $this->triggerLockerMock = $this->getMockBuilder(TriggerLocker::class)
            ->setMethods(['createStateMachineLockEntity'])
            ->setConstructorArgs([$this->createOmsQueryContainerMock(), $this->createOmsConfig()])
            ->getMock();

        return $this->triggerLockerMock;
    }

    /**
     * @return \Spryker\Zed\Oms\Persistence\OmsQueryContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOmsQueryContainerMock()
    {
        $this->omsQueryContainerMock = $this->getMockBuilder(OmsQueryContainer::class)
            ->setMethods(['queryLockItemsByIdentifier'])
            ->getMock();

        return $this->omsQueryContainerMock;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOmsQueryMock()
    {
        $this->omsQueryMock = $this->getMockBuilder(SpyOmsStateMachineLockQuery::class)->setMethods(['count', 'delete'])->getMock();

        return $this->omsQueryMock;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOmsStateMachineLockEntityMock()
    {
        $this->omsStateMachineLockMock = $this->getMockBuilder(SpyOmsStateMachineLock::class)
            ->setMethods(['setIdentifier', 'setExpires', 'save'])
            ->getMock();

        return $this->omsStateMachineLockMock;
    }

    /**
     * @return \Spryker\Zed\Oms\OmsConfig
     */
    protected function createOmsConfig(): OmsConfig
    {
        return new OmsConfig();
    }

    /**
     * @return array
     */
    protected function createOrderItems(): array
    {
        $orderItem1 = (new SpySalesOrderItem())
            ->setIdSalesOrderItem(10);

        $orderItem2 = (new SpySalesOrderItem())
            ->setIdSalesOrderItem(11);

        $orderItem3 = (new SpySalesOrderItem())
            ->setIdSalesOrderItem(12);

        return [$orderItem1, $orderItem2, $orderItem3];
    }

    /**
     * @param array $orderItems
     *
     * @return string
     */
    protected function getOrderItemsIdentifier(array $orderItems): string
    {
        $orderItemIds = [];
        foreach ($orderItems as $orderItem) {
            $orderItemIds[] = $orderItem->getIdSalesOrderItem();
        }

        return implode('-', $orderItemIds);
    }
}
