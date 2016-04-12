<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\StateMachine\Persistence;

interface StateMachineQueryContainerInterface
{
    /**
     * @param int $idStateMachineState
     * @param int $idStateMachineProcess
     * @param string $stateMachineName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateMachineItemStateByIdStateIdProcessAndStateMachineName($idStateMachineState, $idStateMachineProcess, $stateMachineName);


    /**
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
    );

    /**
     * @api
     *
     * @param \DateTime $expirationDate
     * @param string $stateMachineName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function queryItemsWithExpiredTimeout(\DateTime $expirationDate, $stateMachineName);

    /**
     * @api
     *
     * @param int $identifier
     * @param int $idStateMachineProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function queryItemHistoryByStateItemIdentifier($identifier, $idStateMachineProcess);

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    public function queryProcessByStateMachineAndProcessName($stateMachineName, $processName);

    /**
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
    );
}
