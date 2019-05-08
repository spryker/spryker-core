<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\StateMachine\Business\StateMachine;

interface StateUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processes
     * @param string[] $sourceStates
     *
     * @return void
     */
    public function updateStateMachineItemState(array $stateMachineItems, array $processes, array $sourceStates);
}
