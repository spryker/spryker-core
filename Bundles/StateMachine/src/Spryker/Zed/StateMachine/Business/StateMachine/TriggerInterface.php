<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface TriggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return int
     */
    public function triggerForNewStateMachineItem(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    );

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfers
     *
     * @return int
     */
    public function triggerEvent($eventName, array $stateMachineItemTransfers);

    /**
     * @param string $stateMachineName
     *
     * @return int
     */
    public function triggerConditionsWithoutEvent($stateMachineName);

    /**
     * @param string $stateMachineName
     *
     * @return int
     */
    public function triggerForTimeoutExpiredItems($stateMachineName);
}
