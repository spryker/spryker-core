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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idState
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryStateByIdState($idState);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemsWithExistingHistory(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \DateTime $expirationDate
     * @param string $stateMachineName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function queryItemsWithExpiredTimeout(DateTime $expirationDate, $stateMachineName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $identifier
     * @param int $idStateMachineProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function queryItemHistoryByStateItemIdentifier($identifier, $idStateMachineProcess);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    public function queryProcessByStateMachineAndProcessName($stateMachineName, $processName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
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
     * Specification:
     * - TODO: Add method specification.
     *
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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProcess
     * @param string $stateName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function queryItemStateByIdProcessAndStateName($idProcess, $stateName);

    /**
     * Specification:
     * - TODO: Add method specification.
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
    public function queryLockedItemsByIdentifierAndExpirationDate($identifier, DateTime $expirationDate);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \DateTime $expirationDate
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function queryLockedItemsByExpirationDate(DateTime $expirationDate);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $identifier
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function queryLockItemsByIdentifier($identifier);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $processName
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    public function queryProcessByProcessName($processName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $identifier
     * @param int $fkProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function queryEventTimeoutByIdentifierAndFkProcess($identifier, $fkProcess);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param int $transitionToIdState
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function queryLastHistoryItem(StateMachineItemTransfer $stateMachineItemTransfer, $transitionToIdState);
}
