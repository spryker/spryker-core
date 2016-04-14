<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\StateMachine\Business\StateMachine;

interface StateUpdaterInterface
{
    /**
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param array $sourceStateBuffer
     *
     * @return void
     */
    public function updateStateMachineItemState($stateMachineName, array $stateMachineItems, array $processes, array $sourceStateBuffer);
}
