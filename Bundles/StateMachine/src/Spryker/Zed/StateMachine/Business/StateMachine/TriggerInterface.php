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
     * @return bool
     */
    public function triggerForNewStateMachineItem(
        StateMachineProcessTransfer $stateMachineProcessTransfer,
        $identifier
    );

    /**
     * @param string $eventName
     * @param string $stateMachineName
     * @param array $items
     *
     * @return bool
     */
    public function triggerEvent($eventName, $stateMachineName, array $items);

    /**
     * @param string $stateMachineName
     *
     * @return int
     */
    public function triggerConditionsWithoutEvent($stateMachineName);

}
