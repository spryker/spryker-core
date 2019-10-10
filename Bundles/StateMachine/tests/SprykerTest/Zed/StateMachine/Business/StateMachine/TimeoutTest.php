<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Business\StateMachine;

use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Process\Event;
use Spryker\Zed\StateMachine\Business\Process\Process;
use Spryker\Zed\StateMachine\Business\Process\State;
use Spryker\Zed\StateMachine\Business\Process\Transition;
use Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\Timeout;
use SprykerTest\Zed\StateMachine\Mocks\StateMachineMocks;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group StateMachine
 * @group TimeoutTest
 * Add your own group annotations below this line
 */
class TimeoutTest extends StateMachineMocks
{
    public const STATE_WITH_TIMEOUT = 'State with timeout';

    /**
     * @return void
     */
    public function testSetTimeoutShouldStoreNewTimeout()
    {
        $stateMachinePersistenceMock = $this->createPersistenceMock();

        $stateMachinePersistenceMock->expects($this->once())
            ->method('dropTimeoutByItem')
            ->with($this->isInstanceOf(StateMachineItemTransfer::class));

        $stateMachinePersistenceMock->expects($this->once())
            ->method('saveStateMachineItemTimeout')
            ->with(
                $this->isInstanceOf(StateMachineItemTransfer::class),
                $this->isInstanceOf(DateTime::class),
                $this->isType('string')
            );

        $timeout = $this->createTimeout($stateMachinePersistenceMock);
        $timeout->setNewTimeout(
            $this->createProcess(),
            $this->createStateMachineItemTransfer()
        );
    }

    /**
     * @return void
     */
    public function testDropOldTimeoutShouldRemoveExpiredTimeoutsFromPersistence()
    {
        $stateMachinePersistenceMock = $this->createPersistenceMock();

        $stateMachinePersistenceMock->expects($this->once())
            ->method('dropTimeoutByItem')
            ->with($this->isInstanceOf(StateMachineItemTransfer::class));

        $timeout = $this->createTimeout($stateMachinePersistenceMock);
        $timeout->dropOldTimeout(
            $this->createProcess(),
            static::STATE_WITH_TIMEOUT,
            $this->createStateMachineItemTransfer()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\Process
     */
    protected function createProcess()
    {
        $process = new Process();

        $outgoingTransitions = new Transition();
        $event = new Event();
        $event->setName('Timeout event');
        $event->setTimeout('1 DAY');
        $outgoingTransitions->setEvent($event);

        $state = new State();
        $state->setName(static::STATE_WITH_TIMEOUT);
        $state->addOutgoingTransition($outgoingTransitions);

        $process->addState($state);

        return $process;
    }

    /**
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createStateMachineItemTransfer()
    {
        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setStateName(static::STATE_WITH_TIMEOUT);

        return $stateMachineItemTransfer;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface $persistenceMock
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\Timeout
     */
    protected function createTimeout(PersistenceInterface $persistenceMock)
    {
        if ($persistenceMock === null) {
            $persistenceMock = $this->createPersistenceMock();
        }

        return new Timeout($persistenceMock);
    }
}
