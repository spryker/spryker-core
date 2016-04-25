<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Business\Lock;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface;
use Spryker\Zed\StateMachine\Business\Lock\LockedTrigger;
use Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface;
use Unit\Spryker\Zed\StateMachine\Mocks\StateMachineMocks;

class LockedTriggerTest extends StateMachineMocks
{

    /**
     * @return void
     */
    public function testTriggerForNewItemShouldBeWrappedInLockAndTransaction()
    {
        $triggerMock = $this->createTriggerMock();
        $triggerMock->expects($this->once())->method('triggerForNewStateMachineItem');

        $itemLockMock = $this->createItemLockMock();
        $itemLockMock->expects($this->once())
             ->method('isLocked')
             ->willReturn(false);

        $itemLockMock->expects($this->once())
            ->method('acquire');

        $itemLockMock->expects($this->once())
            ->method('release');

        $propelConnectionMock = $this->createPropelConnectionMock();
        $propelConnectionMock->expects($this->once())->method('beginTransaction');
        $propelConnectionMock->expects($this->once())->method('commit');

        $lockedTrigger = $this->createLockedTrigger($triggerMock, $itemLockMock, $propelConnectionMock);
        $lockedTrigger->triggerForNewStateMachineItem(new StateMachineProcessTransfer(), 1);
    }


    /**
     * @return void
     */
    public function testTriggerEventShouldBeWrappedInLockAndTransaction()
    {
        $triggerMock = $this->createTriggerMock();
        $triggerMock->expects($this->once())->method('triggerEvent');

        $itemLockMock = $this->createItemLockMock();
        $itemLockMock->expects($this->once())
            ->method('isLocked')
            ->willReturn(false);

        $itemLockMock->expects($this->once())
            ->method('acquire');

        $itemLockMock->expects($this->once())
            ->method('release');

        $propelConnectionMock = $this->createPropelConnectionMock();
        $propelConnectionMock->expects($this->once())->method('beginTransaction');
        $propelConnectionMock->expects($this->once())->method('commit');

        $lockedTrigger = $this->createLockedTrigger($triggerMock, $itemLockMock, $propelConnectionMock);
        $lockedTrigger->triggerEvent('event', 'sm name', []);
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface $triggerMock
     * @param \Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface $itemLockMock
     * @param \Propel\Runtime\Connection\ConnectionInterface $propelConnectionMock
     *
     * @return \Spryker\Zed\StateMachine\Business\Lock\LockedTrigger
     */
    public function createLockedTrigger(
        TriggerInterface $triggerMock = null,
        ItemLockInterface $itemLockMock = null,
        ConnectionInterface $propelConnectionMock = null
    ) {

        if ($triggerMock === null) {
            $triggerMock = $this->createTriggerMock();
        }

        if ($itemLockMock === null) {
            $itemLockMock = $this->createItemLockMock();
        }

        if ($propelConnectionMock === null) {
            $propelConnectionMock = $this->createPropelConnectionMock();
        }

        return new LockedTrigger(
            $triggerMock,
            $itemLockMock,
            $propelConnectionMock
        );
    }

}
