<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;
use Spryker\Zed\StateMachine\Business\Process\EventInterface;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;

class Timeout implements TimeoutInterface
{
    /**
     * @var \DateTime[]
     */
    protected $eventToTimeoutBuffer = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\StateInterface[]
     */
    protected $stateIdToModelBuffer = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    protected $stateMachinePersistence;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface $stateMachinePersistence
     */
    public function __construct(PersistenceInterface $stateMachinePersistence)
    {
        $this->stateMachinePersistence = $stateMachinePersistence;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function setNewTimeout(ProcessInterface $process, StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $targetState = $this->getStateFromProcess($stateMachineItemTransfer->getStateName(), $process);
        if (!$targetState->hasTimeoutEvent()) {
            return;
        }

        $events = $targetState->getTimeoutEvents();
        $handledEvents = [];
        $currentTime = new DateTime('now');
        foreach ($events as $event) {
            if (in_array($event->getName(), $handledEvents)) {
                continue;
            }

            $handledEvents[] = $event->getName();
            $timeoutDate = $this->calculateTimeoutDateFromEvent($currentTime, $event);

            $this->stateMachinePersistence->dropTimeoutByItem($stateMachineItemTransfer);

            $this->stateMachinePersistence->saveStateMachineItemTimeout($stateMachineItemTransfer, $timeoutDate, $event->getName());
        }
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @param string $stateName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function dropOldTimeout(
        ProcessInterface $process,
        $stateName,
        StateMachineItemTransfer $stateMachineItemTransfer
    ) {
        $sourceState = $this->getStateFromProcess($stateName, $process);

        if ($sourceState->hasTimeoutEvent()) {
            $this->stateMachinePersistence->dropTimeoutByItem($stateMachineItemTransfer);
        }
    }

    /**
     * @param \DateTime $currentTime
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     *
     * @return \DateTime
     */
    protected function calculateTimeoutDateFromEvent(DateTime $currentTime, EventInterface $event)
    {
        if (!isset($this->eventToTimeoutBuffer[$event->getName()])) {
            $timeout = $event->getTimeout();
            $interval = DateInterval::createFromDateString($timeout);

            $this->validateTimeout($interval, $timeout);

            $this->eventToTimeoutBuffer[$event->getName()] = $currentTime->add($interval);
        }

        return $this->eventToTimeoutBuffer[$event->getName()];
    }

    /**
     * @param string $stateName
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    protected function getStateFromProcess($stateName, ProcessInterface $process)
    {
        if (!isset($this->stateIdToModelBuffer[$stateName])) {
            $this->stateIdToModelBuffer[$stateName] = $process->getStateFromAllProcesses($stateName);
        }

        return $this->stateIdToModelBuffer[$stateName];
    }

    /**
     * @param \DateInterval $interval
     * @param string $timeout
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     *
     * @return int
     */
    protected function validateTimeout(DateInterval $interval, $timeout)
    {
        $intervalProperties = get_object_vars($interval);
        $intervalSum = 0;
        foreach ($intervalProperties as $intervalValue) {
            $intervalSum += (int)$intervalValue;
        }

        if ($intervalSum === 0) {
            throw new StateMachineException(
                sprintf(
                    'Invalid format for timeout "%s"',
                    $timeout
                )
            );
        }

        return $intervalSum;
    }
}
