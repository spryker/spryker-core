<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use DateInterval;
use DateTime;
use ErrorException;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\StateMachine\Persistence\Base\SpyStateMachineEventTimeoutQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeout;
use Spryker\Shared\Library\Log;
use Spryker\Zed\StateMachine\Business\Process\EventInterface;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;

class Timeout implements TimeoutInterface
{

    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \DateTime[]
     */
    protected $eventToTimeoutBuffer = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\StateInterface[]
     */
    protected $stateIdToModelBuffer = [];

    /**
     * @var StateMachineHandlerInterface
     */
    protected $stateMachineHandler;

    /**
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $queryContainer
     * @param StateMachineHandlerInterface $stateMachineHandler
     */
    public function __construct(
        StateMachineQueryContainerInterface $queryContainer,
        StateMachineHandlerInterface $stateMachineHandler
    ) {
        $this->queryContainer = $queryContainer;
        $this->stateMachineHandler = $stateMachineHandler;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\StateMachineInterface $stateMachine
     *
     * @return int
     */
    public function checkTimeouts(StateMachineInterface $stateMachine)
    {
        $stateMachineItems = $this->findItemsWithExpiredTimeouts();

        $groupedStateMachineItems = $this->groupItemsByEvent($stateMachineItems);
        foreach ($groupedStateMachineItems as $event => $stateMachineItems) {
            $stateMachine->triggerEvent($event, $stateMachineItems);
        }

        return count($stateMachineItems);
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     * @param \DateTime $currentTime
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function setNewTimeout(
        ProcessInterface $process,
        StateMachineItemTransfer $stateMachineItemTransfer,
        DateTime $currentTime
    ) {
        $targetState = $this->getStateFromProcess($stateMachineItemTransfer->getStateName(), $process);

        if ($targetState->hasTimeoutEvent()) {
            $events = $targetState->getTimeoutEvents();

            $handledEvents = [];
            foreach ($events as $event) {
                if (in_array($event->getName(), $handledEvents)) {
                    continue;
                }

                $handledEvents[] = $event->getName();
                $timeoutDate = $this->calculateTimeoutDateFromEvent($currentTime, $event);

                (new SpyStateMachineEventTimeout())
                    ->setTimeout($timeoutDate)
                    ->setIdentifier($stateMachineItemTransfer->getIdentifier())
                    ->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState())
                    ->setFkStateMachineProcess($stateMachineItemTransfer->getIdStateMachineProcess())
                    ->setEvent($event->getName())
                    ->save();
            }
        }
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @param string $stateId
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function dropOldTimeout(
        ProcessInterface $process,
        $stateId,
        StateMachineItemTransfer $stateMachineItemTransfer
    ) {
        $sourceState = $this->getStateFromProcess($stateId, $process);

        if ($sourceState->hasTimeoutEvent()) {
            SpyStateMachineEventTimeoutQuery::create()
                ->filterByIdentifier($stateMachineItemTransfer->getIdentifier())
                ->delete();
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
        $currentTime = clone $currentTime;

        if (!isset($this->eventToTimeoutBuffer[$event->getName()])) {
            $timeout = $event->getTimeout();
            $interval = DateInterval::createFromDateString($timeout);

            $this->validateTimeout($interval, $timeout);

            $this->eventToTimeoutBuffer[$event->getName()] = $currentTime->add($interval);

            Log::log($this->eventToTimeoutBuffer, 'timeout.log');
        }

        return $this->eventToTimeoutBuffer[$event->getName()];
    }

    /**
     * @param string $stateId
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    protected function getStateFromProcess($stateId, ProcessInterface $process)
    {
        if (!isset($this->stateIdToModelBuffer[$stateId])) {
            $this->stateIdToModelBuffer[$stateId] = $process->getStateFromAllProcesses($stateId);
        }

        return $this->stateIdToModelBuffer[$stateId];
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return array
     */
    protected function groupItemsByEvent(array $stateMachineItems)
    {
        $groupedStateMachineItems = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $eventName = $stateMachineItemTransfer->getEventName();
            if (!isset($groupedStateMachineItems[$eventName])) {
                $groupedStateMachineItems[$eventName] = [];
            }
            $groupedStateMachineItems[$eventName][] = $stateMachineItemTransfer;
        }

        return $groupedStateMachineItems;
    }

    /**
     * @return StateMachineItemTransfer[] $expiredStateMachineItemsTransfer
     */
    protected function findItemsWithExpiredTimeouts()
    {
        $stateMachineExpiredItems = $this->queryContainer
            ->queryItemsWithExpiredTimeout(new DateTime('now'), $this->stateMachineHandler->getStateMachineName())
            ->find();

        $expiredStateMachineItemsTransfer = [];
        foreach ($stateMachineExpiredItems as $stateMachineEventTimeoutEntity) {
            $stateMachineEventTimeoutEntity->getState()->getIdStateMachineItemState();

            $stateMachineItemTransfer = new StateMachineItemTransfer();
            $stateMachineItemTransfer->setEventName($stateMachineEventTimeoutEntity->getEvent());
            $stateMachineItemTransfer->setIdentifier($stateMachineEventTimeoutEntity->getIdentifier());

            $stateMachineItemStateEntity = $stateMachineEventTimeoutEntity->getState();
            $stateMachineItemTransfer->setIdItemState($stateMachineItemStateEntity->getIdStateMachineItemState());
            $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());

            $stateMachineProcessEntity = $stateMachineItemStateEntity->getProcess();
            $stateMachineItemTransfer->setProcessName($stateMachineProcessEntity->getName());
            $stateMachineItemTransfer->setIdStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess());

            $expiredStateMachineItemsTransfer[] = $stateMachineItemTransfer;

        }

        return $expiredStateMachineItemsTransfer;
    }

    /**
     * @param \DateInterval $interval
     * @param mixed $timeout
     *
     * @throws \ErrorException
     *
     * @return int
     */
    protected function validateTimeout($interval, $timeout)
    {
        $vars = get_object_vars($interval);
        $vSum = 0;
        foreach ($vars as $v) {
            $vSum += (int)$v;
        }
        if ($vSum === 0) {
            throw new ErrorException('Invalid format for timeout "' . $timeout . '"');
        }

        return $vSum;
    }

}
