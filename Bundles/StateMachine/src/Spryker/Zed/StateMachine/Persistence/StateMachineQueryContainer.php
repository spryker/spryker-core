<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Persistence;

use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineEventTimeoutTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineProcessTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachinePersistenceFactory getFactory()
 */
class StateMachineQueryContainer extends AbstractQueryContainer implements StateMachineQueryContainerInterface
{
    /**
     * @param int $idStateMachineState
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateMachineItemStateByIdSateMachineState($idStateMachineState)
    {
        return $this->getFactory()->createStateMachineItemStateQuery()
            ->innerJoinProcess()
            ->filterByIdStateMachineItemState($idStateMachineState);
    }

    /**
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
     * @param array $states
     * @param int $idStateMachineProcess
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryStateMachineItemsByState(array $states, $idStateMachineProcess)
    {
        return $this->getFactory()->createStateMachineItemStateQuery()
            ->joinProcess()
            ->where(SpyStateMachineItemStateTableMap::COL_FK_STATE_MACHINE_PROCESS . ' = ?', $idStateMachineProcess)
            ->where(SpyStateMachineItemStateTableMap::COL_NAME . " IN ('" . implode("', '", $states) . "')");
    }

    /**
     * @param int $identifier
     *
     * @return $this|\Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function queryItemHistoryByStateItemIdentifier($identifier)
    {
         return $this->getFactory()
             ->createStateMachineItemStateHistoryQuery()
             ->joinState()
             ->filterByIdentifier($identifier)
             ->orderByCreatedAt();
    }
}
