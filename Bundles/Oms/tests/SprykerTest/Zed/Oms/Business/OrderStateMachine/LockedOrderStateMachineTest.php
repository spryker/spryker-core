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
     * different StateMachine methods, this data provider is used to test similar cases for these methods, with multiple
     * order items. Reducing the amount of code 5 times.
     *
     * @return array
     */
    public function triggerEventsDataProvider(): array
    {
        $eventId = 'eventId';
        $orderItems = $this->createOrderItems();
        $orderItemIds = [10, 11, 12];

        return [
            ['triggerEvent', $orderItemIds, $eventId, $orderItems, []],
            ['triggerEventForNewItem', $orderItemIds,  $orderItems, []],
            ['triggerEventForNewOrderItems', $orderItemIds, $orderItemIds, []],
            ['triggerEventForOrderItems', $orderItemIds, $eventId, $orderItemIds, []],
        ];
    }


    /**
     * @return void
     */
    public function testTriggerSimilarEventsWhenTriggerIsLockedAndLockKeyIsString(): void
    {
        $eventId = 'eventId';
        $singleOrderItemIdentifier = 10;

        $lockedStateMachine = $this->createLockedStateMachine();

        $stateMachineLock = $this->createOmsStateMachineLockEntityMock();

        $stateMachineLock->expects($this->once())
            ->method('setIdentifier')
            ->with($singleOrderItemIdentifier);

        $stateMachineLock->expects($this->once())
            ->method('setExpires')
            ->with($this->isInstanceOf(DateTime::class));

        $this->triggerLockerMock->expects($this->once())
            ->method('commit')
            ->willThrowException(new Exception());

        $this->triggerLockerMock->expects($this->once())
            ->method('createStateMachineLockEntity')
            ->willReturn($stateMachineLock);

        $this->expectException(LockException::class);
        $lockedStateMachine->triggerEventForOneOrderItem($eventId, $singleOrderItemIdentifier, []);
    }

    /**
     * @dataProvider triggerEventsDataProvider
     *
     * @return void
     */
    public function testTriggerSimilarEventsLockReleasesWhenTriggerSuccessMultipleOrderItems(): void
    {
        $arguments = func_get_args();
        $methodToTest = array_shift($arguments);
        $expectedIdentifiers = array_shift($arguments);

        $lockedStateMachine = $this->createLockedStateMachine();

        $this->expectStateMachineLockSaveSuccess($expectedIdentifiers);
        $this->expectTriggerRelease($expectedIdentifiers);

        call_user_func_array([$lockedStateMachine, $methodToTest], $arguments);
    }

    /**
     * @dataProvider triggerEventsDataProvider
     *
     * @return void
     */
    public function testTriggerSimilarEventsLockReleasesWhenTriggerSuccessSingleOrderItems(): void
    {
        $arguments = func_get_args();
        $methodToTest = array_shift($arguments);
        $expectedIdentifiers = array_shift($arguments);

        $lockedStateMachine = $this->createLockedStateMachine();

        $this->expectStateMachineLockSaveSuccess($expectedIdentifiers);
        $this->expectTriggerRelease($expectedIdentifiers);

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
//     */
//    public function testCheckConditionMethodIsDecorated(): void
//    {
//        $lockedStateMachine = $this->createLockedStateMachine();
//        $logContext = ['some log context'];
//
//        $this->stateMachineMock->expects($this->once())
//            ->method('checkConditions')
//            ->with($logContext);
//
//        $lockedStateMachine->checkConditions($logContext);
//    }

    /**
     * @return void
     */
//    public function testIdentifierGeneratedSameForOrderIdsDifferentOrderAndNotUnique(): void
//    {
//        $testIdsList1 = ['100', '11', '12', '10', 11, 12];
//        $testIdsList2 = [12, 11, 100, '10'];
//
//        $expectedResult = $this->hashIdentifier('10-11-12-100');
//
//        $lockedStateMachine = $this->createLockedStateMachine();
//
//        $generateIdentifierMethod = new ReflectionMethod($lockedStateMachine, 'buildIdentifierForOrderItemIdsLock');
//        $generateIdentifierMethod->setAccessible(true);
//
//        $this->assertEquals($expectedResult, $generateIdentifierMethod->invoke($lockedStateMachine, $testIdsList1));
//        $this->assertEquals($expectedResult, $generateIdentifierMethod->invoke($lockedStateMachine, $testIdsList2));
//    }

    /**
     * @param array $expectedIdentifier
     *
     * @return void
     */
    protected function expectStateMachineLockSaveSuccess(array $expectedIdentifier): void
    {
        [$firstIdentifier, $secondIdentifier, $thirdIdentifier] = $expectedIdentifier;

        $stateMachineLock = $this->createOmsStateMachineLockEntityMock();

        $stateMachineLock->expects($this->exactly(3))
            ->method('setIdentifier')
            ->withConsecutive([$firstIdentifier], [$secondIdentifier], [$thirdIdentifier]);

        $stateMachineLock->expects($this->exactly(3))
            ->method('setExpires')
            ->with($this->isInstanceOf(DateTime::class));

        $this->triggerLockerMock->expects($this->exactly(3))
            ->method('createStateMachineLockEntity')
            ->willReturn($stateMachineLock);

        $this->triggerLockerMock->expects($this->exactly(3))
            ->method('persist');

        $this->triggerLockerMock->expects($this->exactly(1))
            ->method('commit');
    }

    /**
     * @param array $identifiers
     *
     * @return void
     */
    protected function expectTriggerRelease(array $identifiers): void
    {
        $queryMock = $this->createOmsQueryMock();
        $queryMock->expects($this->once())
            ->method('delete');

        $this->omsQueryContainerMock->expects($this->once())
            ->method('queryLockItemsByIdentifiers')
            ->with($identifiers)
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
    protected function createStateMachineMock(): OrderStateMachineInterface
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
    protected function createTriggerLockerMock(): TriggerLocker
    {
        $this->triggerLockerMock = $this->getMockBuilder(TriggerLocker::class)
            ->setMethods(['createStateMachineLockEntity', 'persist', 'commit'])
            ->setConstructorArgs([$this->createOmsQueryContainerMock(), $this->createOmsConfig()])
            ->getMock();

        return $this->triggerLockerMock;
    }

    /**
     * @return \Spryker\Zed\Oms\Persistence\OmsQueryContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOmsQueryContainerMock(): OmsQueryContainer
    {
        $this->omsQueryContainerMock = $this->getMockBuilder(OmsQueryContainer::class)
            ->setMethods(['queryLockItemsByIdentifier', 'queryLockItemsByIdentifiers'])
            ->getMock();

        return $this->omsQueryContainerMock;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOmsQueryMock(): SpyOmsStateMachineLockQuery
    {
        $this->omsQueryMock = $this->getMockBuilder(SpyOmsStateMachineLockQuery::class)->setMethods(['count', 'delete'])->getMock();

        return $this->omsQueryMock;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createOmsStateMachineLockEntityMock(): SpyOmsStateMachineLock
    {
        $this->omsStateMachineLockMock = $this->getMockBuilder(SpyOmsStateMachineLock::class)
            ->setMethods(['setIdentifier', 'setExpires', 'persist', 'commit'])
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
