<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Persistence;

use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineEventTimeoutTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineProcessTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachinePersistenceFactory getFactory()
 */
class StateMachineQueryContainer extends AbstractQueryContainer implements StateMachineQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idStateMachineState
     * @param int $idStateMachineProcess
     * @param string $stateMachineName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateMachineItemStateByIdStateIdProcessAndStateMachineName(
        $idStateMachineState,
        $idStateMachineProcess,
        $stateMachineName
    ) {
        return $this->getFactory()->createStateMachineItemStateQuery()
            ->innerJoinProcess()
            ->useProcessQuery()
               ->filterByIdStateMachineProcess($idStateMachineProcess)
               ->filterByStateMachineName($stateMachineName)
            ->endUse()
            ->filterByIdStateMachineItemState($idStateMachineState);
    }

    /**
     * @api
     *
     * @param int $idStateMachineState
     * @param int $idStateMachineProcess
     * @param string $stateMachineName
     * @param int $identifier
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateMachineItemsWithExistingHistory(
        $idStateMachineState,
        $idStateMachineProcess,
        $stateMachineName,
        $identifier
    ) {
        return $this->getFactory()->createStateMachineItemStateQuery()
            ->innerJoinProcess()
            ->useProcessQuery()
               ->filterByIdStateMachineProcess($idStateMachineProcess)
               ->filterByStateMachineName($stateMachineName)
            ->endUse()
            ->useStateHistoryQuery()
               ->filterByIdentifier($identifier)
            ->endUse()
            ->filterByIdStateMachineItemState($idStateMachineState);
    }

    /**
     * @api
     *
     * @param \DateTime $expirationDate
     * @param string $stateMachineName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function queryItemsWithExpiredTimeout(\DateTime $expirationDate, $stateMachineName)
    {
        return $this->getFactory()->createStateMachineEventTimeoutQuery()
            ->innerJoinState()
            ->innerJoinProcess()
            ->where(SpyStateMachineEventTimeoutTableMap::COL_TIMEOUT . ' < ? ', $expirationDate->format('Y-m-d H:i:s'), \PDO::PARAM_STR)
            ->where(SpyStateMachineProcessTableMap::COL_STATE_MACHINE_NAME . ' = ? ', $stateMachineName, \PDO::PARAM_STR)
            ->withColumn(SpyStateMachineEventTimeoutTableMap::COL_EVENT, 'event');
    }

    /**
     * @api
     *
     * @param int $identifier
     * @param int $idStateMachineProcess
     *
     * @return $this|\Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function queryItemHistoryByStateItemIdentifier($identifier, $idStateMachineProcess)
    {
         return $this->getFactory()
             ->createStateMachineItemStateHistoryQuery()
             ->useStateQuery()
                ->filterByFkStateMachineProcess($idStateMachineProcess)
             ->endUse()
             ->joinState()
             ->filterByIdentifier($identifier)
             ->orderByCreatedAt();
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    public function queryProcessByStateMachineAndProcessName($stateMachineName, $processName)
    {
        return $this->getFactory()->createStateMachineProcessQuery()
            ->filterByName($processName)
            ->filterByStateMachineName($stateMachineName);
    }

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     * @param array|string[] $states
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateMachineItemsByIdStateMachineProcessAndItemStates(
        $stateMachineName,
        $processName,
        array $states
    ) {
        return $this->getFactory()->createStateMachineItemStateQuery()
            ->innerJoinStateHistory()
            ->useProcessQuery()
              ->filterByStateMachineName($stateMachineName)
              ->filterByName($processName)
            ->endUse()
            ->joinProcess()
            ->filterByName($states);
    }

    /**
     * @api
     *
     * @param int $idStateMachineProcess
     * @param string $stateName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateMachineItemStateByIdStateMachineProcessAndStateName($idStateMachineProcess, $stateName)
    {
        return $this->getFactory()->createStateMachineItemStateQuery()
            ->filterByFkStateMachineProcess($idStateMachineProcess)
            ->filterByName($stateName);
    }

}
