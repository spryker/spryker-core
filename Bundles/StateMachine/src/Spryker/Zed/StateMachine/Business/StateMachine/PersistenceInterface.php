<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface PersistenceInterface
{

    /**
     * @param string $stateName
     * @param int $idStateMachineProcess
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState
     */
    public function getStateMachineItemStateEntity($stateName, $idStateMachineProcess);

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return int
     */
    public function getProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer);

    /**
     * @param string $stateName
     * @param int $idStateMachineProcess
     *
     * @return int
     */
    public function getInitialStateIdByStateName($stateName, $idStateMachineProcess);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function saveStateMachineItemState(StateMachineItemTransfer $stateMachineItemTransfer, $stateName);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param string $stateMachineName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function updateStateMachineItemsFromPersistence(array $stateMachineItems, $stateMachineName);

    /**
     * @param int $itemIdentifier
     * @param int $idStateMachineProcess
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($itemIdentifier, $idStateMachineProcess);

    /**
     * @param int $idStateMachineState
     * @param int $idStateMachineProcess
     * @param string $stateMachineName
     * @param int $identifier
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer|null
     */
    public function getProcessedStateMachineItemTransfer(
        $idStateMachineState,
        $idStateMachineProcess,
        $stateMachineName,
        $identifier
    );

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param string $stateMachineName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getProcessedStateMachineItems(array $stateMachineItems, $stateMachineName);

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

}
