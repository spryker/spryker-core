<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Business\Lock;

use Orm\Zed\StateMachine\Persistence\Base\SpyStateMachineLockQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLock;
use Spryker\Zed\StateMachine\Business\Lock\ItemLock;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;
use Unit\Spryker\Zed\StateMachine\Mocks\StateMachineMocks;

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

        $lockResult = $itemLock->acquire(1);

        $this->assertTrue($lockResult);
    }

    /**
     * @return void
     */
    public function testIsLockedShouldReturnBooleanWhenItemIsLocked()
    {
        $stateMachineQueryContainerMock = $this->createStateMachineQueryContainerMock();

        $itemLockQuery = $this->createStateMachineQueryMock();
        $itemLockQuery->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $stateMachineQueryContainerMock->expects($this->once())
            ->method('queryLockedItemsByIdentifierAndExpirationDate')
            ->willReturn($itemLockQuery);

        $itemLock = $this->createItemLock(null, $stateMachineQueryContainerMock);

        $lockResult = $itemLock->isLocked(1);

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
     *
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineLock
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $stateMachineQueryContainerMock
     *
     * @return \Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface
     */
    protected function createItemLock(
        SpyStateMachineLock $stateMachineLockEntityMock = null,
        StateMachineQueryContainerInterface $stateMachineQueryContainerMock = null
    ) {

        if ($stateMachineQueryContainerMock === null) {
            $stateMachineQueryContainerMock = $this->createStateMachineQueryContainerMock();
        }

        $stateMachineConfigMock = $this->createStateMachineConfigMock();

        $itemLockPartialMock = $this->getMock(
            ItemLock::class,
            ['createStateMachineLockEntity'],
            [$stateMachineQueryContainerMock, $stateMachineConfigMock]
        );

        $itemLockPartialMock->method('createStateMachineLockEntity')->willReturn($stateMachineLockEntityMock);

        return $itemLockPartialMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\StateMachine\Persistence\SpyStateMachineLock
     */
    protected function createStateMachineItemLockEntityMock()
    {
        $stateMachineLockEntityMock = $this->getMock(SpyStateMachineLock::class);

        return $stateMachineLockEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\StateMachine\Persistence\Base\SpyStateMachineLockQuery
     */
    protected function createStateMachineQueryMock()
    {
        return $this->getMock(SpyStateMachineLockQuery::class);
    }

}
