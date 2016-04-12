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
     * @var StateMachineHandlerInterface
     */
    protected $stateMachineHandler;

    /**
     * @var StateMachineQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface $builder
     * @param StateMachineHandlerInterface $stateMachineHandler
     * @param StateMachineQueryContainerInterface $queryContainer
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
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return StateMachineItemTransfer[]
     */
    public function updateStateMachineItemsFromPersistence(array $stateMachineItems)
    {
        $updatedStateMachineItems = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateMachineItemTransfer->requireIdItemState()
                ->requireIdStateMachineProcess();

            $updatedStateMachineItemTransfer = $this->getStateMachineItemTransferByIdStateAndProcessName(
                $stateMachineItemTransfer->getIdItemState(),
                $stateMachineItemTransfer->getIdStateMachineProcess()
            );

            if ($updatedStateMachineItemTransfer === null) {
                continue;
            }

            $updatedStateMachineItems[] = $stateMachineItemTransfer->fromArray(
                $updatedStateMachineItemTransfer->modifiedToArray(),
                true
            );
        }

        return $updatedStateMachineItems;
    }

    /**
     *
     * @param int $idStateMachineState
     * @param string $idStateMachineProcess
     * @return StateMachineItemTransfer|null
     */
    protected function getStateMachineItemTransferByIdStateAndProcessName($idStateMachineState, $idStateMachineProcess)
    {
        $stateMachineItemStateEntity = $this->queryContainer
            ->queryStateMachineItemStateByIdStateIdProcessAndStateMachineName(
                $idStateMachineState,
                $idStateMachineProcess,
                $this->stateMachineHandler->getStateMachineName()
            )
            ->findOne();

        if ($stateMachineItemStateEntity === null) {
            return null;
        }

        $stateMachineProcessEntity = $stateMachineItemStateEntity->getProcess();

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());
        $stateMachineItemTransfer->setIdItemState($idStateMachineState);
        $stateMachineItemTransfer->setIdStateMachineProcess(
            $stateMachineProcessEntity->getIdStateMachineProcess()
        );
        $stateMachineItemTransfer->setProcessName($stateMachineProcessEntity->getName());

        return $stateMachineItemTransfer;
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return StateMachineItemTransfer[]
     */
    public function getProcessedStateMachineItems(array $stateMachineItems)
    {
        $updatedStateMachineItems = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $stateMachineItemTransfer->requireIdItemState()
                ->requireIdStateMachineProcess()
                ->requireIdentifier();

            $updatedStateMachineItemTransfer = $this->getProcessedStateMachineItemTransfer(
                $stateMachineItemTransfer->getIdItemState(),
                $stateMachineItemTransfer->getIdStateMachineProcess(),
                $stateMachineItemTransfer->getIdentifier()
            );

            if ($updatedStateMachineItemTransfer === null) {
                continue;
            }

            $updatedStateMachineItems[] = $stateMachineItemTransfer->fromArray(
                $updatedStateMachineItemTransfer->modifiedToArray(),
                true
            );
        }

        return $updatedStateMachineItems;
    }

    /**
     * @param int $idStateMachineState
     * @param int $idStateMachineProcess
     * @param int $identifier
     *
     * @return StateMachineItemTransfer|null
     */
    public function getProcessedStateMachineItemTransfer(
        $idStateMachineState,
        $idStateMachineProcess,
        $identifier
    ) {
        $stateMachineItemStateEntity = $this->queryContainer
            ->queryStateMachineItemsWithExistingHistory(
                $idStateMachineState,
                $idStateMachineProcess,
                $this->stateMachineHandler->getStateMachineName(),
                $identifier
            )
            ->findOne();

        if ($stateMachineItemStateEntity === null) {
            return null;
        }

        $stateMachineProcessEntity = $stateMachineItemStateEntity->getProcess();

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setIdentifier($identifier);
        $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());
        $stateMachineItemTransfer->setIdItemState($idStateMachineState);
        $stateMachineItemTransfer->setIdStateMachineProcess(
            $stateMachineProcessEntity->getIdStateMachineProcess()
        );
        $stateMachineItemTransfer->setProcessName($stateMachineProcessEntity->getName());

        return $stateMachineItemTransfer;
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
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
     * @param StateMachineItemTransfer $stateMachineItemTransfer
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
     * @param int $itemIdentifier
     * @param int $idStateMachineProcess
     *
     * @return StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($itemIdentifier, $idStateMachineProcess)
    {
        $stateMachineHistoryItems = $this->queryContainer
            ->queryItemHistoryByStateItemIdentifier($itemIdentifier, $idStateMachineProcess)
            ->find();

        $stateMachineItems = [];
        foreach ($stateMachineHistoryItems as $stateMachineHistoryItemEntity) {
            $itemStateEntity = $stateMachineHistoryItemEntity->getState();
            $processEntity = $itemStateEntity->getProcess();

            $stateMachineItemTransfer = new StateMachineItemTransfer();
            $stateMachineItemTransfer->setIdentifier($itemIdentifier);
            $stateMachineItemTransfer->setStateName($itemStateEntity->getName());
            $stateMachineItemTransfer->setIdItemState($itemStateEntity->getIdStateMachineItemState());
            $stateMachineItemTransfer->setIdStateMachineProcess($processEntity->getIdStateMachineProcess());

            $stateMachineItems[] = $stateMachineItemTransfer;
        }

        return $stateMachineItems;
    }

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     *
     * @return StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag)
    {
        return $this->getItemsByFlag($stateMachineProcessTransfer, $flag, true);
    }

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     *
     * @return StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag)
    {
        return $this->getItemsByFlag($stateMachineProcessTransfer, $flag, false);
    }

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     * @param bool $hasFlag
     *
     * @return StateMachineItemTransfer[]
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
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     * @param bool $hasFlag
     *
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface[]
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
}
