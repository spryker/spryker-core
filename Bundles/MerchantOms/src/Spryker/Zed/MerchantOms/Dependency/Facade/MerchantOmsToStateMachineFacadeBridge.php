<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Dependency\Facade;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;

class MerchantOmsToStateMachineFacadeBridge implements MerchantOmsToStateMachineFacadeInterface
{
    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface
     */
    protected $stateMachineFacade;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     */
    public function __construct($stateMachineFacade)
    {
        $this->stateMachineFacade = $stateMachineFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @return int
     */
    public function triggerForNewStateMachineItem(StateMachineProcessTransfer $stateMachineProcessTransfer, $identifier)
    {
        return $this->stateMachineFacade->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return int
     */
    public function triggerEvent($eventName, StateMachineItemTransfer $stateMachineItemTransfer)
    {
        return $this->stateMachineFacade->triggerEvent($eventName, $stateMachineItemTransfer);
    }

    /**
     * @param string $eventName
     * @param array $stateMachineItems
     *
     * @return int
     */
    public function triggerEventForItems($eventName, array $stateMachineItems)
    {
        return $this->stateMachineFacade->triggerEventForItems($eventName, $stateMachineItems);
    }
}
