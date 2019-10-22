<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface;
use Spryker\Zed\StateMachine\Business\Process\StateInterface;

interface ConditionInterface
{
    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $transitions
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $sourceState
     * @param \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface $transactionLogger
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function getTargetStatesFromTransitions(
        array $transitions,
        StateMachineItemTransfer $stateMachineItemTransfer,
        StateInterface $sourceState,
        TransitionLogInterface $transactionLogger
    );

    /**
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[][]
     */
    public function getOnEnterEventsForStatesWithoutTransition($stateMachineName, $processName);
}
