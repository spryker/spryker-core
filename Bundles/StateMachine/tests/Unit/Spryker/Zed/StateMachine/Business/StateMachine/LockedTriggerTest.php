<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\Exception\LockException;
use Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\LockedTrigger;
use Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface;
use Unit\Spryker\Zed\StateMachine\Mocks\StateMachineMocks;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group StateMachine
 * @group LockedTriggerTest
 */
class LockedTriggerTest extends StateMachineMocks
{

    /**
     * @return void
     */
    public function testTriggerForNewItemWhenLockedShouldThrowException()
    {
        $this->expectException(LockException::class);

        $triggerMock = $this->createTriggerMock();

        $itemLockMock = $this->createItemLockMock();

        $itemLockMock->method('acquire')
            ->willThrowException(new LockException());

        $lockedTrigger = $this->createLockedTrigger($triggerMock, $itemLockMock);
        $lockedTrigger->triggerForNewStateMachineItem(new StateMachineProcessTransfer(), 1);
    }


    /**
     * @return void
     */
    public function testTriggerEventForNewItemWhenLockedShouldThrowException()
    {
        $this->expectException(LockException::class);

        $triggerMock = $this->createTriggerMock();

        $itemLockMock = $this->createItemLockMock();

        $itemLockMock->method('acquire')
            ->willThrowException(new LockException());

        $lockedTrigger = $this->createLockedTrigger($triggerMock, $itemLockMock);
        $lockedTrigger->triggerEvent('event', []);
    }



    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface $triggerMock
     * @param \Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface $itemLockMock
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\LockedTrigger
     */
    public function createLockedTrigger(TriggerInterface $triggerMock = null, ItemLockInterface $itemLockMock = null)
    {
        if ($triggerMock === null) {
            $triggerMock = $this->createTriggerMock();
        }

        if ($itemLockMock === null) {
            $itemLockMock = $this->createItemLockMock();
        }

        return new LockedTrigger(
            $triggerMock,
            $itemLockMock
        );
    }

}
