<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Lock;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\Exception\LockException;
use Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface;

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
    public function __construct(
        TriggerInterface $stateMachineTrigger,
        ItemLockInterface $itemLock
    ) {
        $this->itemLock = $itemLock;
        $this->stateMachineTrigger = $stateMachineTrigger;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     * @return bool
     * @throws \Spryker\Zed\StateMachine\Business\Exception\LockException
     */
    public function triggerForNewStateMachineItem(StateMachineProcessTransfer $stateMachineProcessTransfer, $identifier)
    {
        if ($this->itemLock->isLocked($identifier)) {
            throw new LockException('State machine item is locked.');
        }

        $this->itemLock->acquire($identifier);
        $triggerResult = $this->stateMachineTrigger->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);
        $this->itemLock->release($identifier);

        return $triggerResult;
    }

    /**
     * @param string $eventName
     * @param string $stateMachineName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @return bool
     * @throws \Spryker\Zed\StateMachine\Business\Exception\LockException
     */
    public function triggerEvent($eventName, $stateMachineName, array $stateMachineItems)
    {
        $identifier = $this->buildIdentifierForMultipleItemLock($stateMachineItems);
        if ($this->itemLock->isLocked($identifier)) {
            throw new LockException('State machine item is locked.');
        }

        $this->itemLock->acquire($identifier);
        $triggerEventResult = $this->stateMachineTrigger->triggerEvent(
            $eventName,
            $stateMachineName,
            $stateMachineItems
        );
        $this->itemLock->release($identifier);

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
    protected function buildIdentifierForMultipleItemLock($stateMachineItems)
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

}
