<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;

class Finder implements FinderInterface
{

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @var \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface
     */
    protected $stateMachineHandler;

    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $queryContainer
     */
    public function __construct(
        BuilderInterface $builder,
        StateMachineHandlerInterface $stateMachineHandler,
        StateMachineQueryContainerInterface $queryContainer
    ) {
        $this->builder = $builder;
        $this->stateMachineHandler = $stateMachineHandler;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function getProcesses()
    {
        $processes = [];
        foreach ($this->stateMachineHandler->getActiveProcesses() as $processName) {
            $builder = clone $this->builder;
            $stateMachineProcessTransfer = new StateMachineProcessTransfer();
            $stateMachineProcessTransfer->setProcessName($processName);
            $stateMachineProcessTransfer->setStateMachineName($this->stateMachineHandler->getStateMachineName());
            $processes[$processName] = $builder->createProcess($stateMachineProcessTransfer);
        }

        return $processes;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return array|string[]
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
     * @return array|string[]
     */
    public function getManualEventsForStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineItemTransfer->requireProcessName();

        $processName = $stateMachineItemTransfer->getProcessName();

        $processBuilder = clone $this->builder;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName($this->stateMachineHandler->getStateMachineName());

        $manualEvents = $processBuilder->createProcess($stateMachineProcessTransfer)->getManualEventsBySource();

        $stateName = $stateMachineItemTransfer->getStateName();
        if (isset($manualEvents[$stateName])) {
            return $manualEvents[$stateName];
        }

        return [];

    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag)
    {
        return $this->getItemsByFlag($stateMachineProcessTransfer, $flag, true);
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag)
    {
        return $this->getItemsByFlag($stateMachineProcessTransfer, $flag, false);
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     * @param bool $hasFlag
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    protected function getItemsByFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName, $hasFlag)
    {
        $stateMachineProcessTransfer->requireProcessName()->requireStateMachineName();

        $statesByFlag = $this->getStatesByFlag($stateMachineProcessTransfer, $flagName, $hasFlag);
        if (count($statesByFlag) === 0) {
            return [];
        }

        $stateMachineProcessEntity = $this->queryContainer->queryProcessByStateMachineAndProcessName(
            $stateMachineProcessTransfer->getStateMachineName(),
            $stateMachineProcessTransfer->getProcessName()
        )->findOne();

        if ($stateMachineProcessEntity === null) {
            return [];
        }

        $stateMachineItems = $this->queryContainer->queryStateMachineItemsByIdStateMachineProcessAndItemStates(
            $stateMachineProcessTransfer->getStateMachineName(),
            $stateMachineProcessTransfer->getProcessName(),
            array_keys($statesByFlag)
        )->find();

        $stateMachineItemsWithFlag = [];
        foreach ($stateMachineItems as $stateMachineItemEntity) {

            $stateMachineItemTransfer = new StateMachineItemTransfer();
            $stateMachineItemTransfer->setProcessName($stateMachineProcessTransfer->getProcessName());
            $stateMachineItemTransfer->setIdItemState($stateMachineItemEntity->getIdStateMachineItemState());
            $stateMachineItemTransfer->setIdStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess());
            $stateMachineItemTransfer->setStateName($stateMachineItemEntity->getName());
            $itemIdentifier = $stateMachineItemEntity->getStateHistories()->getFirst()->getIdentifier();
            $stateMachineItemTransfer->setIdentifier($itemIdentifier);

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

        $builder = clone $this->builder;
        $processStateList = $builder->createProcess($stateMachineProcessTransfer)->getAllStates();
        foreach ($processStateList as $state) {
            if (($hasFlag && $state->hasFlag($flag)) || (!$hasFlag && !$state->hasFlag($flag))) {
                $selectedStates[$state->getName()] = $state;
            }
        }

        return $selectedStates;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     *
     * @return array
     */
    public function filterItemsWithOnEnterEvent(
        array $stateMachineItems,
        array $processes,
        array $sourceStateBuffer = []
    ) {
        $itemsWithOnEnterEvent = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();
            $processName = $stateMachineItemTransfer->getProcessName();

            if (!isset($processes[$processName])) {
                throw new StatemachineException(
                    sprintf(
                        'Unknown process for state machine "%s".',
                        $processName
                    )
                );
            }

            $process = $processes[$processName];
            $targetState = $process->getStateFromAllProcesses($stateName);

            if (isset($sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()])) {
                $sourceState = $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()];
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

}
