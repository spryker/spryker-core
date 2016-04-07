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
    public function getStateMachineItemsFromPersistence(array $stateMachineItems)
    {
        $updatedStateMachineItems = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $updatedStateMachineItems[$stateMachineItemTransfer->getIdentifier()] = $this->getStateMachineItemFromPersistence($stateMachineItemTransfer);
        }

        return $updatedStateMachineItems;
    }

    /**
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return StateMachineItemTransfer
     */
    public function getStateMachineItemFromPersistence(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineItemTransfer->requireIdItemState();

        $stateMachineItemStateEntity = $this->queryContainer
            ->queryStateMachineItemStateByIdSateMachineState($stateMachineItemTransfer->getIdItemState())
            ->findOne();

        $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());
        $stateMachineItemTransfer->setIdStateMachineProcess($stateMachineItemStateEntity->getProcess()->getIdStateMachineProcess());
        $stateMachineItemTransfer->setProcessName($stateMachineItemStateEntity->getProcess()->getName());

        return $stateMachineItemTransfer;
    }

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface[]
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems)
    {
        $itemsWithManualEvents = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $manualEvents = $this->getManualEventsForStateMachineItem($stateMachineItemTransfer);

            $stateName = $stateMachineItemTransfer->getStateName();
            if (isset($manualEvents[$stateName])) {
                $itemsWithManualEvents[$stateMachineItemTransfer->getIdentifier()] = $manualEvents[$stateName];
            }
        }

        return $itemsWithManualEvents;
    }

    /**
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface
     */
    public function getManualEventsForStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineItemTransfer->requireProcessName();

        $processName = $stateMachineItemTransfer->getProcessName();

        $processBuilder = clone $this->builder;

        $stateMachineProcessTransfer = new StateMachineProcessTransfer();
        $stateMachineProcessTransfer->setProcessName($processName);
        $stateMachineProcessTransfer->setStateMachineName($this->stateMachineHandler->getStateMachineName());

        return $processBuilder->createProcess($stateMachineProcessTransfer)->getManualEventsBySource();
    }

    /**
     * @param int $identifier
     *
     * @return StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($identifier)
    {
        $stateMachineHistoryItems = $this->queryContainer
            ->queryItemHistoryByStateItemIdentifier($identifier)
            ->find();

        $stateMachineItems = [];
        foreach ($stateMachineHistoryItems as $stateMachineHistoryItemEntity) {
            $itemStateEntity = $stateMachineHistoryItemEntity->getState();
            $processEntity = $itemStateEntity->getProcess();
            $stateMachineItemTransfer = new StateMachineItemTransfer();
            $stateMachineItemTransfer->setIdentifier($identifier);
            $stateMachineItemTransfer->setStateName($itemStateEntity->getName());
            $stateMachineItemTransfer->setIdItemState($itemStateEntity->getIdStateMachineItemState());
            $stateMachineItemTransfer->setIdStateMachineProcess($processEntity->getIdStateMachineProcess());
            $stateMachineItems[] = $stateMachineItemTransfer;
        }

        return $stateMachineItems;
    }
}
