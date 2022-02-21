<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Persistence;

use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineEventTimeoutTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineProcessTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachinePersistenceFactory getFactory()
 */
class StateMachineQueryContainer extends AbstractQueryContainer implements StateMachineQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idState
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateByIdState($idState)
    {
        return $this->getFactory()
            ->createStateMachineItemStateQuery()
            ->innerJoinProcess()
            ->filterByIdStateMachineItemState($idState);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemsWithExistingHistory(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        return $this->getFactory()
            ->createStateMachineItemStateQuery()
            ->innerJoinProcess()
            ->useStateHistoryQuery()
               ->filterByIdentifier($stateMachineItemTransfer->getIdentifier())
            ->endUse()
            ->filterByIdStateMachineItemState($stateMachineItemTransfer->getIdItemState());
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \DateTime $expirationDate
     * @param string $stateMachineName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function queryItemsWithExpiredTimeout(DateTime $expirationDate, $stateMachineName)
    {
        return $this->getFactory()
            ->createStateMachineEventTimeoutQuery()
            ->innerJoinState()
            ->innerJoinProcess()
            ->where(SpyStateMachineEventTimeoutTableMap::COL_TIMEOUT . ' < ? ', $expirationDate->format('Y-m-d H:i:s'), PDO::PARAM_STR)
            ->where(SpyStateMachineProcessTableMap::COL_STATE_MACHINE_NAME . ' = ? ', $stateMachineName, PDO::PARAM_STR)
            ->withColumn(SpyStateMachineEventTimeoutTableMap::COL_EVENT, 'event');
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $identifier
     * @param int $idStateMachineProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function queryItemHistoryByStateItemIdentifier($identifier, $idStateMachineProcess)
    {
        /** @phpstan-var \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery */
        return $this->getFactory()
            ->createStateMachineItemStateHistoryQuery()
            ->filterByIdentifier($identifier)
            ->orderByCreatedAt()
            ->orderByIdStateMachineItemStateHistory()
            ->joinState()
            ->useStateQuery()
                ->filterByFkStateMachineProcess($idStateMachineProcess)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    public function queryProcessByStateMachineAndProcessName($stateMachineName, $processName)
    {
        return $this->getFactory()
            ->createStateMachineProcessQuery()
            ->filterByName($processName)
            ->filterByStateMachineName($stateMachineName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     * @param array<string> $states
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemsByIdStateMachineProcessAndItemStates(
        $stateMachineName,
        $processName,
        array $states
    ) {
        /** @phpstan-var \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery */
        return $this->getFactory()
            ->createStateMachineItemStateQuery()
            ->filterByName($states, Criteria::IN)
            ->orderByIdStateMachineItemState()
            ->joinProcess()
            ->useProcessQuery()
              ->filterByStateMachineName($stateMachineName)
              ->filterByName($processName)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     * @param array<string> $states
     * @param string $historySortDirection
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemsByStateMachineProcessNameAndItemStates(
        $stateMachineName,
        $processName,
        array $states,
        string $historySortDirection
    ) {
        /** @phpstan-var \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery */
        return $this->getFactory()
            ->createStateMachineItemStateQuery()
            ->filterByName($states, Criteria::IN)
            ->innerJoinWithStateHistory()
            ->joinProcess()
            ->useProcessQuery()
                ->filterByStateMachineName($stateMachineName)
                ->filterByName($processName)
            ->endUse()
            ->useStateHistoryQuery()
                ->orderByCreatedAt($historySortDirection)
                ->orderByIdStateMachineItemStateHistory($historySortDirection)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProcess
     * @param string $stateName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemStateByIdProcessAndStateName($idProcess, $stateName)
    {
        return $this->getFactory()
            ->createStateMachineItemStateQuery()
            ->filterByFkStateMachineProcess($idProcess)
            ->filterByName($stateName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Not used, will be removed in the next major release.
     *
     * @param string $identifier
     * @param \DateTime $expirationDate
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function queryLockedItemsByIdentifierAndExpirationDate($identifier, DateTime $expirationDate)
    {
        return $this->getFactory()
            ->createStateMachineLockQuery()
            ->filterByIdentifier($identifier)
            ->filterByExpires(['min' => $expirationDate], Criteria::GREATER_EQUAL);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \DateTime $expirationDate
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function queryLockedItemsByExpirationDate(DateTime $expirationDate)
    {
        return $this->getFactory()
            ->createStateMachineLockQuery()
            ->filterByExpires(['max' => $expirationDate], Criteria::LESS_EQUAL);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $identifier
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function queryLockItemsByIdentifier($identifier)
    {
        return $this->getFactory()
            ->createStateMachineLockQuery()
            ->filterByIdentifier($identifier);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $processName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    public function queryProcessByProcessName($processName)
    {
        return $this->getFactory()
            ->createStateMachineProcessQuery()
            ->filterByName($processName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $identifier
     * @param int $fkProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function queryEventTimeoutByIdentifierAndFkProcess($identifier, $fkProcess)
    {
        return $this->getFactory()
            ->createStateMachineEventTimeoutQuery()
            ->filterByIdentifier($identifier)
            ->filterByFkStateMachineProcess($fkProcess);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param int $transitionToIdState
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function queryLastHistoryItem(StateMachineItemTransfer $stateMachineItemTransfer, $transitionToIdState)
    {
        /** @phpstan-var \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery */
        return $this->getFactory()->createStateMachineItemStateHistoryQuery()
            ->filterByIdentifier($stateMachineItemTransfer->getIdentifier())
            ->orderByCreatedAt(Criteria::DESC)
            ->orderByIdStateMachineItemStateHistory(Criteria::DESC)
            ->limit(1)
            ->useStateQuery()
               ->filterByFkStateMachineProcess($stateMachineItemTransfer->getIdStateMachineProcess())
            ->endUse();
    }
}
