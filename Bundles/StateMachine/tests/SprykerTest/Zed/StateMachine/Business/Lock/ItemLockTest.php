<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Business\Lock;

use Orm\Zed\StateMachine\Persistence\SpyStateMachineLock;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\StateMachine\Business\Exception\LockException;
use Spryker\Zed\StateMachine\Business\Lock\ItemLock;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;
use SprykerTest\Zed\StateMachine\Mocks\StateMachineMocks;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group Lock
 * @group ItemLockTest
 * Add your own group annotations below this line
 */
class ItemLockTest extends StateMachineMocks
{
    /**
     * @return void
     */
    public function testAcquireLockShouldCreateItemWithLockInPersistence()
    {
        $stateMachineLockEntityMock = $this->createStateMachineItemLockEntityMock();
        $stateMachineLockEntityMock->method('save')
            ->willReturn(1);

        $itemLock = $this->createItemLock($stateMachineLockEntityMock);

        $lockResult = $itemLock->acquire(sha1(1));

        $this->assertTrue($lockResult);
    }

    /**
     * @return void
     */
    public function testAcquireWhenPropelExceptionThrownShouldReThrowLockException()
    {
        $this->expectException(LockException::class);

        $stateMachineLockEntityMock = $this->createStateMachineItemLockEntityMock();
        $stateMachineLockEntityMock->method('save')
            ->willThrowException(new PropelException());

        $itemLock = $this->createItemLock($stateMachineLockEntityMock);

        $lockResult = $itemLock->acquire(sha1(1));

        $this->assertTrue($lockResult);
    }

    /**
     * @return void
     */
    public function testReleaseLockShouldDeleteLockFromDatabase()
    {
        $stateMachineQueryContainerMock = $this->createStateMachineQueryContainerMock();

        $itemLockQuery = $this->createStateMachineQueryMock();
        $itemLockQuery
            ->expects($this->once())
            ->method('delete');

        $stateMachineQueryContainerMock->expects($this->once())
            ->method('queryLockItemsByIdentifier')
            ->willReturn($itemLockQuery);

        $itemLock = $this->createItemLock(null, $stateMachineQueryContainerMock);

        $itemLock->release(1);
    }

    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineLock|null $stateMachineLockEntityMock
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface|null $stateMachineQueryContainerMock
     *
     * @return \Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface
     */
    protected function createItemLock(
        ?SpyStateMachineLock $stateMachineLockEntityMock = null,
        ?StateMachineQueryContainerInterface $stateMachineQueryContainerMock = null
    ) {

        if ($stateMachineQueryContainerMock === null) {
            $stateMachineQueryContainerMock = $this->createStateMachineQueryContainerMock();
        }

        $stateMachineConfigMock = $this->createStateMachineConfigMock();

        $itemLockPartialMock = $this->getMockBuilder(ItemLock::class)
            ->setMethods(['createStateMachineLockEntity'])
            ->setConstructorArgs([$stateMachineQueryContainerMock, $stateMachineConfigMock])
            ->getMock();

        $itemLockPartialMock->method('createStateMachineLockEntity')->willReturn($stateMachineLockEntityMock);

        return $itemLockPartialMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\StateMachine\Persistence\SpyStateMachineLock
     */
    protected function createStateMachineItemLockEntityMock()
    {
        $stateMachineLockEntityMock = $this->getMockBuilder(SpyStateMachineLock::class)->getMock();

        return $stateMachineLockEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\StateMachine\Persistence\Base\SpyStateMachineLockQuery
     */
    protected function createStateMachineQueryMock()
    {
        return $this->getMockBuilder(SpyStateMachineLockQuery::class)->getMock();
    }
}
