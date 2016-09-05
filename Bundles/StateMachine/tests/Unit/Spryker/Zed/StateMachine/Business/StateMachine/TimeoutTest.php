<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Process\Event;
use Spryker\Zed\StateMachine\Business\Process\Process;
use Spryker\Zed\StateMachine\Business\Process\State;
use Spryker\Zed\StateMachine\Business\Process\Transition;
use Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\Timeout;
use Unit\Spryker\Zed\StateMachine\Mocks\StateMachineMocks;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group StateMachine
 * @group Business
 * @group StateMachine
 * @group TimeoutTest
 */
class TimeoutTest extends StateMachineMocks
{

    const STATE_WITH_TIMEOUT = 'State with timeout';

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
                $this->isInstanceOf(\DateTime::class),
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
    public function testDropOldTimeoutShouldRemoveExpiredTimeoutsFromPersitence()
    {
        $stateMachinePersistenceMock = $this->createPersistenceMock();

        $stateMachinePersistenceMock->expects($this->once())
            ->method('dropTimeoutByItem')
            ->with($this->isInstanceOf(StateMachineItemTransfer::class));

        $timeout = $this->createTimeout($stateMachinePersistenceMock);
        $timeout->dropOldTimeout(
            $this->createProcess(),
            self::STATE_WITH_TIMEOUT,
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
        $state->setName(self::STATE_WITH_TIMEOUT);
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
        $stateMachineItemTransfer->setStateName(self::STATE_WITH_TIMEOUT);

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
