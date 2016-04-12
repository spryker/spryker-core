<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface FinderInterface
{
    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function getProcesses();

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return array|string[]
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems);

    /**
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return array|string[]
     */
    public function getManualEventsForStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return StateMachineItemTransfer[]
     */
    public function updateStateMachineItemsFromPersistence(array $stateMachineItems);

    /**
     * @param int $itemIdentifier
     * @param int $idStateMachineProcess
     *
     * @return StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($itemIdentifier, $idStateMachineProcess);

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     *
     * @return StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag);

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     *
     * @return StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag);

    /**
     * @param int $idStateMachineState
     * @param int $idStateMachineProcess
     * @param int $identifier
     *
     * @return StateMachineItemTransfer|null
     */
    public function getProcessedStateMachineItemTransfer($idStateMachineState, $idStateMachineProcess, $identifier);

    /**
     * @param StateMachineItemTransfer[] $stateMachineItems
     *
     * @return StateMachineItemTransfer[]
     */
    public function getProcessedStateMachineItems(array $stateMachineItems);

}
