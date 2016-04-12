<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery;
use Propel\Runtime\Propel;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;

class Persistence implements PersistenceInterface
{

    /**
     * @var \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState[]
     */
    protected static $stateEntityBuffer = [];

    /**
     * @var \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess[]
     */
    protected static $processEntityBuffer = [];

    /**
     * @var \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    protected $persistedStates;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface
     */
    protected $timeout;

    /**
     * @var \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface
     */
    protected $stateMachineHandler;

    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface $timeout
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $stateMachineQueryContainer
     */
    public function __construct(
        TimeoutInterface $timeout,
        StateMachineHandlerInterface $stateMachineHandler,
        StateMachineQueryContainerInterface $stateMachineQueryContainer
    ) {
        $this->timeout = $timeout;
        $this->stateMachineHandler = $stateMachineHandler;
        $this->queryContainer = $stateMachineQueryContainer;
    }

    /**
     * @param string $stateName
     * @param int $idStateMachineProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getStateMachineItemStateEntity($stateName, $idStateMachineProcess)
    {
        if (array_key_exists($stateName, self::$stateEntityBuffer)) {
            return self::$stateEntityBuffer[$stateName];
        }

        $stateMachineItemStateEntity = SpyStateMachineItemStateQuery::create()
            ->filterByName($stateName)
            ->filterByFkStateMachineProcess($idStateMachineProcess)
            ->findOne();

        if (!isset($stateMachineItemStateEntity)) {
            $stateMachineItemStateEntity = new SpyStateMachineItemState();
            $stateMachineItemStateEntity->setName($stateName);
            $stateMachineItemStateEntity->setFkStateMachineProcess($idStateMachineProcess);
            $stateMachineItemStateEntity->save();
        }

        $stateBuffer[$stateName] = $stateMachineItemStateEntity;

        return $stateMachineItemStateEntity;
    }


    /**
     * @param int $itemIdentifier
     * @param int $idStateMachineProcess
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
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
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return int
     */
    public function getProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        if (array_key_exists($stateMachineProcessTransfer->getProcessName(), self::$processEntityBuffer)) {
            return self::$processEntityBuffer[$stateMachineProcessTransfer->getProcessName()];
        }

        $stateMachineProcessEntity = SpyStateMachineProcessQuery::create()->findOneByName(
            $stateMachineProcessTransfer->getProcessName()
        );

        if (!isset($stateMachineProcessEntity)) {
            $stateMachineProcessEntity = new SpyStateMachineProcess();
            $stateMachineProcessEntity->setName($stateMachineProcessTransfer->getProcessName());
            $stateMachineProcessEntity->setStateMachineName($stateMachineProcessTransfer->getStateMachineName());
            $stateMachineProcessEntity->save();
        }

        $processBuffer[$stateMachineProcessTransfer->getProcessName()] = $stateMachineProcessEntity;

        return $stateMachineProcessEntity->getIdStateMachineProcess();
    }

    /**
     * @param string $stateName
     * @param int $idStateMachineProcess
     *
     * @return int|null
     */
    public function getInitialStateIdByStateName($stateName, $idStateMachineProcess)
    {
        $stateMachineItemStateEntity = $this->getStateMachineItemStateEntity($stateName, $idStateMachineProcess);

        if ($stateMachineItemStateEntity === null) {
            return null;
        }

        return $stateMachineItemStateEntity->getIdStateMachineItemState();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function saveStateMachineItemState(StateMachineItemTransfer $stateMachineItemTransfer, $stateName)
    {
        if (isset($this->persistedStates[$stateName])) {
            $stateMachineItemStateEntity = $this->persistedStates[$stateName];
        } else {
            $stateMachineItemTransfer->requireIdStateMachineProcess();

            $stateMachineItemStateEntity = $this->queryContainer
                ->queryStateMachineItemStateByIdStateMachineProcessAndStateName(
                    $stateMachineItemTransfer->getIdStateMachineProcess(),
                    $stateName
                )->findOne();

            if (!isset($stateMachineItemStateEntity)) {
                $stateMachineItemStateEntity = new SpyStateMachineItemState();
                $stateMachineItemStateEntity->setName($stateName);
                $stateMachineItemStateEntity->setFkStateMachineProcess($stateMachineItemTransfer->getIdStateMachineProcess());
                $stateMachineItemStateEntity->save();
            }
            $this->persistedStates[$stateName] = $stateMachineItemStateEntity;
        }

        $stateMachineItemTransfer->setIdItemState($stateMachineItemStateEntity->getIdStateMachineItemState());
        $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());

        return $stateMachineItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @return void
     */
    public function updateStateMachineItemState(array $stateMachineItems, array $processes, array $sourceStateBuffer)
    {
        $connection = Propel::getConnection();
        $connection->beginTransaction();

        $currentTime = new \DateTime('now');
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $process = $processes[$stateMachineItemTransfer->getProcessName()];

            $sourceState = $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()];
            $targetState = $stateMachineItemTransfer->getStateName();

            if ($sourceState !== $targetState) {
                $this->timeout->dropOldTimeout($process, $sourceState, $stateMachineItemTransfer);
                $this->timeout->setNewTimeout($process, $stateMachineItemTransfer, $currentTime);
                $this->stateMachineHandler->itemStateUpdated($stateMachineItemTransfer);

                $this->saveItemStateHistory($stateMachineItemTransfer);

            }
        }

        $connection->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    protected function saveItemStateHistory(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineItemStateHistory = new SpyStateMachineItemStateHistory();
        $stateMachineItemStateHistory->setIdentifier($stateMachineItemTransfer->getIdentifier());
        $stateMachineItemStateHistory->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState());
        $stateMachineItemStateHistory->save();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
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
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer|null
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
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
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
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer|null
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
     * @param string $processName
     * @param string $stateMachineName
     * @param string[] $states
     *
     * @return int[]
     */
    public function getStateMachineItemIdsByStatesProcessAndStateMachineName(
        $processName,
        $stateMachineName,
        array $states
    ) {
        $stateMachineStateItems = $this->queryContainer
            ->queryStateMachineItemsByIdStateMachineProcessAndItemStates(
                $stateMachineName,
                $processName,
                $states
            )
            ->find();

        if ($stateMachineStateItems->count() === 0) {
            return [];
        }

        $stateMachineItemStateIds = [];
        foreach ($stateMachineStateItems as $stateMachineItemEntity) {
            $stateMachineItemStateIds[] = $stateMachineItemEntity->getIdStateMachineItemState();
        }

        return $stateMachineItemStateIds;
    }

}
