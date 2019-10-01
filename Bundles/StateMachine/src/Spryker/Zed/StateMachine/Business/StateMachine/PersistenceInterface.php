<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use DateTime;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface PersistenceInterface
{
    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return int
     */
    public function getProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return int
     */
    public function getInitialStateIdByStateName(StateMachineItemTransfer $stateMachineItemTransfer, $stateName);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function saveStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer, $stateName);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function updateStateMachineItemsFromPersistence(array $stateMachineItems);

    /**
     * @param int $itemIdentifier
     * @param int $idStateMachineProcess
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($itemIdentifier, $idStateMachineProcess);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function getProcessedStateMachineItemTransfer(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getProcessedStateMachineItems(array $stateMachineItems);

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
    );

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function saveItemStateHistory(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param \DateTime $timeoutDate
     * @param string $eventName
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeout
     */
    public function saveStateMachineItemTimeout(
        StateMachineItemTransfer $stateMachineItemTransfer,
        DateTime $timeoutDate,
        $eventName
    );

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function dropTimeoutByItem(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * @param string $stateMachineName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $expiredStateMachineItemsTransfer
     */
    public function getItemsWithExpiredTimeouts($stateMachineName);
}
