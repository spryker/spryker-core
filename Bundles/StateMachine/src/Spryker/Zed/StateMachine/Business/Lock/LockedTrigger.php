<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Lock;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
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
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $propelConnection;

    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface $stateMachineTrigger
     * @param \Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface $itemLock
     * @param \Propel\Runtime\Connection\ConnectionInterface $propelConnection
     */
    public function __construct(
        TriggerInterface $stateMachineTrigger,
        ItemLockInterface $itemLock,
        ConnectionInterface $propelConnection
    ) {
        $this->itemLock = $itemLock;
        $this->stateMachineTrigger = $stateMachineTrigger;
        $this->propelConnection = $propelConnection;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier
     * @return int
     * @throws \Spryker\Zed\StateMachine\Business\Exception\LockException
     */
    public function triggerForNewStateMachineItem(StateMachineProcessTransfer $stateMachineProcessTransfer, $identifier)
    {
        $this->propelConnection->beginTransaction();
        if ($this->itemLock->isLocked($identifier)) {
            throw new LockException('State machine item is locked.');
        }

        $this->itemLock->acquire($identifier);
        $this->propelConnection->commit();

        $triggerResult = $this->stateMachineTrigger->triggerForNewStateMachineItem($stateMachineProcessTransfer, $identifier);

        $this->itemLock->release($identifier);

        return $triggerResult;
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     * @return int
     * @throws \Spryker\Zed\StateMachine\Business\Exception\LockException
     */
    public function triggerEvent($eventName, array $stateMachineItems)
    {
        $identifier = $this->buildIdentifierForMultipleItemLock($stateMachineItems);

        $this->propelConnection->beginTransaction();
        if ($this->itemLock->isLocked($identifier)) {
            throw new LockException('State machine item is locked.');
        }

        $this->itemLock->acquire($identifier);
        $this->propelConnection->commit();

        $triggerEventResult = $this->stateMachineTrigger
            ->triggerEvent($eventName, $stateMachineItems);

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
