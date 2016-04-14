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
     * @param string $stateMachineName
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    public function getProcesses($stateMachineName);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param string $stateMachineName
     *
     * @return array|string[]
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems, $stateMachineName);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateMachineName
     *
     * @return array|\string[]
     */
    public function getManualEventsForStateMachineItem(
        StateMachineItemTransfer $stateMachineItemTransfer,
        $stateMachineName
    );

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag);

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flag
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfer
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flag);

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @throws \LogicException
     *
     * @return array
     */
    public function filterItemsWithOnEnterEvent(
        array $stateMachineItems,
        array $processes,
        array $sourceStateBuffer = []
    );

}
