<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;
use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;

class Finder implements FinderInterface
{
    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface
     */
    protected $stateMachineHandlerResolver;

    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface $stateMachineHandlerResolver
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $queryContainer
     */
    public function __construct(
        BuilderInterface $builder,
        HandlerResolverInterface $stateMachineHandlerResolver,
        StateMachineQueryContainerInterface $queryContainer
    ) {
        $this->builder = $builder;
        $this->stateMachineHandlerResolver = $stateMachineHandlerResolver;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param string $stateMachineName
     *
     * @return bool
     */
    public function hasHandler($stateMachineName)
    {
        return $this->stateMachineHandlerResolver->find($stateMachineName) !== null;
    }

    /**
     * @param string $stateMachineName
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer[]
     */
    public function getProcesses($stateMachineName)
    {
        $processes = [];
        $stateMachineHandler = $this->stateMachineHandlerResolver->get($stateMachineName);
        foreach ($stateMachineHandler->getActiveProcesses() as $processName) {
            $processes[$processName] = $this->createStateMachineProcessTransfer($stateMachineName, $processName);
        }

        return $processes;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return string[][]
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems)
    {
        $itemsWithManualEvents = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $manualEvents = $this->getManualEventsForStateMachineItem($stateMachineItemTransfer);

            if (count($manualEvents) > 0) {
                $itemsWithManualEvents[$stateMachineItemTransfer->getIdentifier()] = $manualEvents;
            }
        }

        return $itemsWithManualEvents;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return string[]
     */
    public function getManualEventsForStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineItemTransfer->requireProcessName();

        $processName = $stateMachineItemTransfer->getProcessName();

        $processBuilder = clone $this->builder;

        $stateMachineProcessTransfer = $this->createStateMachineProcessTransfer(
            $stateMachineItemTransfer->getStateMachineName(),
            $processName
        );

        $process = $processBuilder->createProcess($stateMachineProcessTransfer);
        $manualEvents = $process->getManuallyExecutableEventsBySource();

        $stateName = $stateMachineItemTransfer->getStateName();
        if (!isset($manualEvents[$stateName])) {
            return [];
        }

        return $manualEvents[$stateName];
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     * @param string $sort
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag, string $sort = 'ASC')
    {
        return $this->getItemsByFlag($stateMachineProcessTransfer, $flag, true, $sort);
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     * @param string $sort
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag, string $sort = 'ASC')
    {
        return $this->getItemsByFlag($stateMachineProcessTransfer, $flag, false, $sort);
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     * @param bool $hasFlag
     * @param string $sort
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    protected function getItemsByFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName, $hasFlag, string $sort)
    {
        $stateMachineProcessTransfer->requireProcessName()->requireStateMachineName();

        $statesByFlag = $this->getStatesByFlag($stateMachineProcessTransfer, $flagName, $hasFlag);
        if (count($statesByFlag) === 0) {
            return [];
        }

        $stateMachineProcessEntity = $this->getStateMachineProcessEntity($stateMachineProcessTransfer);
        if ($stateMachineProcessEntity === null) {
            return [];
        }

        $stateMachineItems = $this->getFlaggedStateMachineItems(
            $stateMachineProcessTransfer,
            array_keys($statesByFlag),
            $sort
        );

        $stateMachineItemsWithFlag = [];
        foreach ($stateMachineItems as $stateMachineItemEntity) {
            $stateMachineItemTransfer = $this->createStateMachineHistoryItemTransfer(
                $stateMachineProcessTransfer,
                $stateMachineItemEntity,
                $stateMachineProcessEntity
            );

            $stateMachineItemsWithFlag[] = $stateMachineItemTransfer;
        }

        return $stateMachineItemsWithFlag;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     * @param bool $hasFlag
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface[]
     */
    protected function getStatesByFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag, $hasFlag)
    {
        $selectedStates = [];

        $processStateList = $this->builder->createProcess($stateMachineProcessTransfer)->getAllStates();

        foreach ($processStateList as $state) {
            if ($hasFlag !== $state->hasFlag($flag)) {
                continue;
            }
            $selectedStates[$state->getName()] = $state;
        }

        return $selectedStates;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param string[] $sourceStates
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[][]
     */
    public function filterItemsWithOnEnterEvent(
        array $stateMachineItems,
        array $processes,
        array $sourceStates = []
    ) {
        $itemsWithOnEnterEvent = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->requireStateName()->getStateName();
            $processName = $stateMachineItemTransfer->requireProcessName()->getProcessName();

            $this->assertProcessExists($processes, $processName);

            $process = $processes[$processName];
            $targetState = $process->getStateFromAllProcesses($stateName);

            if (isset($sourceStates[$stateMachineItemTransfer->getIdentifier()])) {
                $sourceState = $sourceStates[$stateMachineItemTransfer->getIdentifier()];
            } else {
                $sourceState = $process->getStateFromAllProcesses($stateMachineItemTransfer->getStateName());
            }

            if ($sourceState !== $targetState->getName() && $targetState->hasOnEnterEvent()) {
                $eventName = $targetState->getOnEnterEvent()->getName();
                if (array_key_exists($eventName, $itemsWithOnEnterEvent) === false) {
                    $itemsWithOnEnterEvent[$eventName] = [];
                }
                $itemsWithOnEnterEvent[$eventName][] = $stateMachineItemTransfer;
            }
        }

        return $itemsWithOnEnterEvent;
    }

    /**
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    public function findProcessByStateMachineAndProcessName($stateMachineName, $processName)
    {
        $stateMachineProcessTransfer = $this->createStateMachineProcessTransfer($stateMachineName, $processName);

        return $this->builder->createProcess($stateMachineProcessTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function findProcessesForItems(array $stateMachineItems)
    {
        $processes = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $processName = $stateMachineItemTransfer->requireProcessName()->getProcessName();
            if (isset($processes[$processName])) {
                continue;
            }

            $processes[$stateMachineItemTransfer->getProcessName()] = $this->findProcessByStateMachineAndProcessName(
                $stateMachineItemTransfer->getStateMachineName(),
                $stateMachineItemTransfer->getProcessName()
            );
        }

        return $processes;
    }

    /**
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    protected function createStateMachineProcessTransfer($stateMachineName, $processName)
    {
        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setStateMachineName($stateMachineName);
        $stateMachineProcessTransfer->setProcessName($processName);

        return $stateMachineProcessTransfer;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param string $processName
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     *
     * @return void
     */
    protected function assertProcessExists(array $processes, $processName)
    {
        if (!isset($processes[$processName])) {
            throw new StateMachineException(
                sprintf(
                    'Unknown process "%s" for state machine "%s".',
                    $processName,
                    'SM'
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState $stateMachineItemEntity
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess $stateMachineProcessEntity
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createStateMachineHistoryItemTransfer(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        SpyStateMachineItemState $stateMachineItemEntity,
        SpyStateMachineProcess $stateMachineProcessEntity
    ) {

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setProcessName($stateMachineProcessTransfer->getProcessName());
        $stateMachineItemTransfer->setIdItemState($stateMachineItemEntity->getIdStateMachineItemState());
        $stateMachineItemTransfer->setIdStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess());
        $stateMachineItemTransfer->setStateName($stateMachineItemEntity->getName());
        $stateMachineItemTransfer->setStateMachineName($stateMachineProcessEntity->getStateMachineName());
        $stateMachineItemTransfer->setIdentifier($this->getItemIdentifier($stateMachineItemEntity));

        return $stateMachineItemTransfer;
    }

    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState $stateMachineItemEntity
     *
     * @return int|null
     */
    protected function getItemIdentifier(SpyStateMachineItemState $stateMachineItemEntity): ?int
    {
        if ($stateMachineItemEntity->getStateHistories()->count() === 0) {
            return null;
        }

        return $stateMachineItemEntity->getStateHistories()->getFirst()->getIdentifier();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess|null
     */
    protected function getStateMachineProcessEntity(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        return $this->queryContainer->queryProcessByStateMachineAndProcessName(
            $stateMachineProcessTransfer->getStateMachineName(),
            $stateMachineProcessTransfer->getProcessName()
        )->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param array $statesByFlag
     * @param string $historySortDirection
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getFlaggedStateMachineItems(StateMachineProcessTransfer $stateMachineProcessTransfer, array $statesByFlag, string $historySortDirection)
    {
        $itemStateCollection = $this->queryContainer->queryItemsByStateMachineProcessNameAndItemStates(
            $stateMachineProcessTransfer->getStateMachineName(),
            $stateMachineProcessTransfer->getProcessName(),
            $statesByFlag,
            $historySortDirection
        )->find();

        return $itemStateCollection;
    }
}
