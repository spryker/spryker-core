<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeout;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;
use Spryker\Zed\StateMachine\Business\Exception\StateMachineException;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;

class Persistence implements PersistenceInterface
{
    /**
     * @var \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess[]
     */
    protected $processEntityBuffer = [];

    /**
     * @var \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState[]
     */
    protected $persistedStates;

    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $stateMachineQueryContainer
     */
    protected $stateMachineQueryContainer;

    /**
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $stateMachineQueryContainer
     */
    public function __construct(StateMachineQueryContainerInterface $stateMachineQueryContainer)
    {
        $this->stateMachineQueryContainer = $stateMachineQueryContainer;
    }

    /**
     * @param int $itemIdentifier
     * @param int $idStateMachineProcess
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($itemIdentifier, $idStateMachineProcess)
    {
        $stateMachineHistoryItems = $this->stateMachineQueryContainer
            ->queryItemHistoryByStateItemIdentifier($itemIdentifier, $idStateMachineProcess)
            ->find();

        $stateMachineItems = [];
        foreach ($stateMachineHistoryItems as $stateMachineHistoryItemEntity) {
            $stateMachineItemTransfer = $this->createItemTransferForStateHistory(
                $itemIdentifier,
                $stateMachineHistoryItemEntity
            );

            $stateMachineItems[] = $stateMachineItemTransfer;
        }

        return $stateMachineItems;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return int
     */
    public function getProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        $stateMachineProcessTransfer->requireProcessName();

        if (array_key_exists($stateMachineProcessTransfer->getProcessName(), $this->processEntityBuffer)) {
            return $this->processEntityBuffer[$stateMachineProcessTransfer->getProcessName()]
                ->getIdStateMachineProcess();
        }

        $stateMachineProcessEntity = $this->stateMachineQueryContainer
            ->queryProcessByProcessName(
                $stateMachineProcessTransfer->getProcessName()
            )->findOne();

        if ($stateMachineProcessEntity === null) {
            $stateMachineProcessEntity = $this->saveStateMachineProcess($stateMachineProcessTransfer);
        }

        $this->processEntityBuffer[$stateMachineProcessTransfer->getProcessName()] = $stateMachineProcessEntity;

        return $stateMachineProcessEntity->getIdStateMachineProcess();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return int
     */
    public function getInitialStateIdByStateName(StateMachineItemTransfer $stateMachineItemTransfer, $stateName)
    {
        $stateMachineItemTransfer = $this->saveStateMachineItem($stateMachineItemTransfer, $stateName);

        return $stateMachineItemTransfer->getIdItemState();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function saveStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer, $stateName)
    {
        $persistedStateKey = $stateMachineItemTransfer->getIdStateMachineProcess() . $stateName;

        if (isset($this->persistedStates[$persistedStateKey])) {
            $stateMachineItemStateEntity = $this->persistedStates[$persistedStateKey];
        } else {
            $stateMachineItemTransfer->requireIdStateMachineProcess();

            $stateMachineItemStateEntity = $this->stateMachineQueryContainer
                ->queryItemStateByIdProcessAndStateName(
                    $stateMachineItemTransfer->getIdStateMachineProcess(),
                    $stateName
                )->findOne();

            if ($stateMachineItemStateEntity === null) {
                $stateMachineItemStateEntity = $this->saveStateMachineItemEntity($stateMachineItemTransfer, $stateName);
            }
            $this->persistedStates[$persistedStateKey] = $stateMachineItemStateEntity;
        }

        $stateMachineItemTransfer->setIdItemState($stateMachineItemStateEntity->getIdStateMachineItemState());
        $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());
        $stateMachineItemTransfer->setStateMachineName(
            $stateMachineItemStateEntity->getProcess()->getStateMachineName()
        );

        return $stateMachineItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function saveItemStateHistory(StateMachineItemTransfer $stateMachineItemTransfer)
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
            $stateMachineItemTransfer->requireIdentifier()
                ->requireIdItemState();

            $stateMachineItemStateEntity = $this->stateMachineQueryContainer
                ->queryStateByIdState(
                    $stateMachineItemTransfer->getIdItemState()
                )->findOne();

            if ($stateMachineItemStateEntity === null) {
                continue;
            }

            $updatedStateMachineItemTransfer = $this->hydrateItemTransferFromEntity($stateMachineItemStateEntity);

            $updatedStateMachineItems[] = $stateMachineItemTransfer->fromArray(
                $updatedStateMachineItemTransfer->modifiedToArray(),
                true
            );
        }

        return $updatedStateMachineItems;
    }

    /**
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState $stateMachineItemStateEntity
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function hydrateItemTransferFromEntity(SpyStateMachineItemState $stateMachineItemStateEntity)
    {
        $stateMachineProcessEntity = $stateMachineItemStateEntity->getProcess();
        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());
        $stateMachineItemTransfer->setIdItemState($stateMachineItemStateEntity->getIdStateMachineItemState());
        $stateMachineItemTransfer->setIdStateMachineProcess(
            $stateMachineProcessEntity->getIdStateMachineProcess()
        );
        $stateMachineItemTransfer->setProcessName($stateMachineProcessEntity->getName());
        $stateMachineItemTransfer->setStateMachineName($stateMachineProcessEntity->getStateMachineName());

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
                ->requireIdentifier();

            $updatedStateMachineItemTransfer = $this->getProcessedStateMachineItemTransfer($stateMachineItemTransfer);
            $updatedStateMachineItems[] = $stateMachineItemTransfer->fromArray(
                $updatedStateMachineItemTransfer->modifiedToArray(),
                true
            );
        }

        return $updatedStateMachineItems;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\StateMachineException
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function getProcessedStateMachineItemTransfer(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineItemTransfer->requireIdentifier()
            ->requireIdItemState();

        $stateMachineItemStateEntity = $this->stateMachineQueryContainer
            ->queryItemsWithExistingHistory($stateMachineItemTransfer)
            ->findOne();

        if ($stateMachineItemStateEntity === null) {
            throw new StateMachineException('State machine item not found.');
        }

        $stateMachineProcessEntity = $stateMachineItemStateEntity->getProcess();

        $updatedStateMachineItemTransfer = new StateMachineItemTransfer();
        $updatedStateMachineItemTransfer->setIdentifier($stateMachineItemTransfer->getIdentifier());
        $updatedStateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());
        $updatedStateMachineItemTransfer->setIdItemState($stateMachineItemStateEntity->getIdStateMachineItemState());
        $updatedStateMachineItemTransfer->setIdStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess());
        $updatedStateMachineItemTransfer->setProcessName($stateMachineProcessEntity->getName());
        $updatedStateMachineItemTransfer->setStateMachineName($stateMachineProcessEntity->getStateMachineName());

        return $updatedStateMachineItemTransfer;
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
        $stateMachineStateItems = $this->stateMachineQueryContainer
            ->queryItemsByIdStateMachineProcessAndItemStates(
                $stateMachineName,
                $processName,
                $states
            )->find();

        if ($stateMachineStateItems->count() === 0) {
            return [];
        }

        $stateMachineItemStateIds = [];
        foreach ($stateMachineStateItems as $stateMachineItemEntity) {
            $stateMachineItemStateIds[] = $stateMachineItemEntity->getIdStateMachineItemState();
        }

        return $stateMachineItemStateIds;
    }

    /**
     * @param string $stateMachineName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $expiredStateMachineItemsTransfer
     */
    public function getItemsWithExpiredTimeouts($stateMachineName)
    {
        $stateMachineExpiredItems = $this->stateMachineQueryContainer
            ->queryItemsWithExpiredTimeout(
                new DateTime('now'),
                $stateMachineName
            )->find();

        $expiredStateMachineItemsTransfer = [];
        foreach ($stateMachineExpiredItems as $stateMachineEventTimeoutEntity) {
            $stateMachineItemTransfer = new StateMachineItemTransfer();
            $stateMachineItemTransfer->setEventName($stateMachineEventTimeoutEntity->getEvent());
            $stateMachineItemTransfer->setIdentifier($stateMachineEventTimeoutEntity->getIdentifier());

            $stateMachineItemStateEntity = $stateMachineEventTimeoutEntity->getState();
            $stateMachineItemTransfer->setIdItemState($stateMachineItemStateEntity->getIdStateMachineItemState());
            $stateMachineItemTransfer->setStateName($stateMachineItemStateEntity->getName());

            $stateMachineProcessEntity = $stateMachineItemStateEntity->getProcess();
            $stateMachineItemTransfer->setProcessName($stateMachineProcessEntity->getName());
            $stateMachineItemTransfer->setIdStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess());
            $stateMachineItemTransfer->setStateMachineName($stateMachineProcessEntity->getStateMachineName());

            $expiredStateMachineItemsTransfer[] = $stateMachineItemTransfer;
        }

        return $expiredStateMachineItemsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param \DateTime $timeoutDate
     * @param string $eventName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeout
     */
    public function saveStateMachineItemTimeout(
        StateMachineItemTransfer $stateMachineItemTransfer,
        DateTime $timeoutDate,
        $eventName
    ) {

        $stateMachineItemTimeoutEntity = new SpyStateMachineEventTimeout();
        $stateMachineItemTimeoutEntity
            ->setTimeout($timeoutDate)
            ->setIdentifier($stateMachineItemTransfer->getIdentifier())
            ->setFkStateMachineItemState($stateMachineItemTransfer->getIdItemState())
            ->setFkStateMachineProcess($stateMachineItemTransfer->getIdStateMachineProcess())
            ->setEvent($eventName)
            ->save();

        return $stateMachineItemTimeoutEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function dropTimeoutByItem(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $this->stateMachineQueryContainer
            ->queryEventTimeoutByIdentifierAndFkProcess(
                $stateMachineItemTransfer->getIdentifier(),
                $stateMachineItemTransfer->getIdStateMachineProcess()
            )->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess
     */
    protected function saveStateMachineProcess(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        $stateMachineProcessEntity = new SpyStateMachineProcess();
        $stateMachineProcessEntity->setName($stateMachineProcessTransfer->getProcessName());
        $stateMachineProcessEntity->setStateMachineName($stateMachineProcessTransfer->getStateMachineName());
        $stateMachineProcessEntity->save();

        return $stateMachineProcessEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState
     */
    protected function saveStateMachineItemEntity(StateMachineItemTransfer $stateMachineItemTransfer, $stateName)
    {
        $stateMachineItemStateEntity = new SpyStateMachineItemState();
        $stateMachineItemStateEntity->setName($stateName);
        $stateMachineItemStateEntity->setFkStateMachineProcess($stateMachineItemTransfer->getIdStateMachineProcess());
        $stateMachineItemStateEntity->save();

        return $stateMachineItemStateEntity;
    }

    /**
     * @param int $itemIdentifier
     * @param \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory $stateMachineItemHistoryEntity
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createItemTransferForStateHistory(
        $itemIdentifier,
        SpyStateMachineItemStateHistory $stateMachineItemHistoryEntity
    ) {

        $itemStateEntity = $stateMachineItemHistoryEntity->getState();
        $processEntity = $itemStateEntity->getProcess();

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setIdentifier($itemIdentifier);
        $stateMachineItemTransfer->setStateName($itemStateEntity->getName());
        $stateMachineItemTransfer->setIdItemState($itemStateEntity->getIdStateMachineItemState());
        $stateMachineItemTransfer->setIdStateMachineProcess($processEntity->getIdStateMachineProcess());
        $stateMachineItemTransfer->setStateMachineName($processEntity->getStateMachineName());
        $stateMachineItemTransfer->setCreatedAt($stateMachineItemHistoryEntity->getCreatedAt());

        return $stateMachineItemTransfer;
    }
}
