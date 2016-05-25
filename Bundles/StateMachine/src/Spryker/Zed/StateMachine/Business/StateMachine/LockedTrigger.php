<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\Exception\LockException;
use Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface;

class LockedTrigger implements TriggerInterface
{

    /**
     * @var \Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface
     */
    protected $itemLock;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface
     */
    protected $stateMachineTrigger;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface $stateMachineTrigger
     * @param \Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface $itemLock
     */
    public function __construct(TriggerInterface $stateMachineTrigger, ItemLockInterface $itemLock)
    {
        $this->itemLock = $itemLock;
        $this->stateMachineTrigger = $stateMachineTrigger;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\LockException
     *
     * @return int
     *
     */
    public function triggerForNewStateMachineItem(StateMachineProcessTransfer $stateMachineProcessTransfer, $identifier)
    {
        if ($this->itemLock->isLocked($identifier)) {
            throw new LockException('State machine item is locked.');
        }

        $this->itemLock->acquire($identifier);

        try {
            $triggerResult = $this->stateMachineTrigger->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
        } finally {
            $this->itemLock->release($identifier);
        }

        return $triggerResult;
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\LockException
     *
     * @return int
     */
    public function triggerEvent($eventName, array $stateMachineItems)
    {
        $identifier = $this->buildIdentifierForMultipleItemLock($stateMachineItems);

        if ($this->itemLock->isLocked($identifier)) {
            throw new LockException('State machine item is locked.');
        }

        $this->itemLock->acquire($identifier);

        try {
            $triggerEventResult = $this->stateMachineTrigger
                ->triggerEvent($eventName, $stateMachineItems);
        } finally {
            $this->itemLock->release($identifier);
        }

        return $triggerEventResult;
    }

    /**
     * @param string $stateMachineName
     * @return int
     */
    public function triggerConditionsWithoutEvent($stateMachineName)
    {
        return $this->stateMachineTrigger->triggerConditionsWithoutEvent($stateMachineName);
    }


    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return string
     */
    protected function buildIdentifierForMultipleItemLock(array $stateMachineItems)
    {
        $identifier = '';
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            if ($identifier) {
                $identifier .= '-';
            }
            $identifier .= $stateMachineItemTransfer->getIdentifier();
        }

        return $identifier;
    }

    /**
     * @param string $stateMachineName
     *
     * @return int
     */
    public function triggerForTimeoutExpiredItems($stateMachineName)
    {
        return $this->stateMachineTrigger->triggerForTimeoutExpiredItems($stateMachineName);
    }

}
