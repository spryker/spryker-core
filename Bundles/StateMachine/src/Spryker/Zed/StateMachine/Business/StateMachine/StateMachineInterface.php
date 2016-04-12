<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface StateMachineInterface
{

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return bool
     */
    public function triggerForNewStateMachineItem(StateMachineProcessTransfer $stateMachineProcessTransfer, $identifier);

    /**
     * @param int $eventName
     * @param array $items
     *
     * @return bool
     */
    public function triggerEvent($eventName, array $items);

    /**
     * @return bool
     */
    public function checkConditions();

}
