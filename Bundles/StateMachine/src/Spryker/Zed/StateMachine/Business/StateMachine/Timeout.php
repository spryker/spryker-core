<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\StateMachine\Persistence\Base\SpyStateMachineEventTimeoutQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeout;
use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;
use Spryker\Zed\StateMachine\Business\Process\EventInterface;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;
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
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $queryContainer
     */
    public function __construct(StateMachineQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
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
        \DateTime $currentTime
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

                $this->dropTimeoutByItem($stateMachineItemTransfer);

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
     * @param string $stateName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
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
            $this->dropTimeoutByItem($stateMachineItemTransfer);
        }
    }

    /**
     * @param \DateTime $currentTime
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     *
     * @return \DateTime
     */
    protected function calculateTimeoutDateFromEvent(\DateTime $currentTime, EventInterface $event)
    {
        if (!isset($this->eventToTimeoutBuffer[$event->getName()])) {
            $timeout = $event->getTimeout();
            $interval = \DateInterval::createFromDateString($timeout);

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
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return array
     */
    public function groupItemsByEvent(array $stateMachineItems)
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
     * @param string $stateMachineName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $expiredStateMachineItemsTransfer
     */
    public function getItemsWithExpiredTimeouts($stateMachineName)
    {
        $stateMachineExpiredItems = $this->queryContainer
            ->queryItemsWithExpiredTimeout(
                new \DateTime('now'),
                $stateMachineName
            )
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
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
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
            throw new StateMachineException('Invalid format for timeout "' . $timeout . '"');
        }

        return $vSum;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    protected function dropTimeoutByItem(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        SpyStateMachineEventTimeoutQuery::create()
            ->filterByIdentifier($stateMachineItemTransfer->getIdentifier())
            ->filterByFkStateMachineProcess($stateMachineItemTransfer->getIdStateMachineProcess())
            ->delete();
    }

}
