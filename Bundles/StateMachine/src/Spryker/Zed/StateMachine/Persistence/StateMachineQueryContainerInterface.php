<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\StateMachine\Persistence;

use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface StateMachineQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idState
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateByIdState($idState);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemsWithExistingHistory(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * @api
     *
     * @param \DateTime $expirationDate
     * @param string $stateMachineName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function queryItemsWithExpiredTimeout(DateTime $expirationDate, $stateMachineName);

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
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     * @param string[] $states
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemsByIdStateMachineProcessAndItemStates(
        $stateMachineName,
        $processName,
        array $states
    );

    /**
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     * @param string[] $states
     * @param string $historySortDirection
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemsByStateMachineProcessNameAndItemStates(
        $stateMachineName,
        $processName,
        array $states,
        string $historySortDirection
    );

    /**
     * @api
     *
     * @param int $idProcess
     * @param string $stateName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemStateByIdProcessAndStateName($idProcess, $stateName);

    /**
     * @api
     *
     * @deprecated Not used, will be removed in the next major release.
     *
     * @param string $identifier
     * @param \DateTime $expirationDate
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function queryLockedItemsByIdentifierAndExpirationDate($identifier, DateTime $expirationDate);

    /**
     * @api
     *
     * @param \DateTime $expirationDate
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function queryLockedItemsByExpirationDate(DateTime $expirationDate);

    /**
     * @api
     *
     * @param string $identifier
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function queryLockItemsByIdentifier($identifier);

    /**
     * @api
     *
     * @param string $processName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    public function queryProcessByProcessName($processName);

    /**
     * @api
     *
     * @param int $identifier
     * @param int $fkProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function queryEventTimeoutByIdentifierAndFkProcess($identifier, $fkProcess);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param int $transitionToIdState
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function queryLastHistoryItem(StateMachineItemTransfer $stateMachineItemTransfer, $transitionToIdState);
}
