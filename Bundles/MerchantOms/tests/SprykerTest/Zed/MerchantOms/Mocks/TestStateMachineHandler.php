<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Mocks;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

class TestStateMachineHandler implements StateMachineHandlerInterface
{
    /**
     * @var \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected $itemStateUpdated;

    /**
     * List of command plugins for this state machine for all processes.
     *
     * @return array
     */
    public function getCommandPlugins(): array
    {
        return [];
    }

    /**
     * List of condition plugins for this state machine for all processes.
     *
     * @return array
     */
    public function getConditionPlugins(): array
    {
        return [];
    }

    /**
     * Name of state machine used by this handler.
     *
     * @return string
     */
    public function getStateMachineName(): string
    {
        return 'Merchant';
    }

    /**
     * List of active processes used for this state machine
     *
     * @return string[]
     */
    public function getActiveProcesses(): array
    {
        return ['Test01'];
    }

    /**
     * Provide initial state name for item when state machine initialized. Useing proces name.
     *
     * @param string $processName
     *
     * @return string
     */
    public function getInitialStateForProcess($processName): string
    {
        return 'new';
    }

    /**
     * This method is called when state of item was changed, client can create custom logic for example update it's related table with new state id/name.
     * StateMachineItemTransfer:identifier is id of entity from implementor.
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function itemStateUpdated(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $this->itemStateUpdated = $stateMachineItemTransfer;

        return true;
    }

    /**
     * This method should return all item identifiers which are in passed state ids.
     *
     * @param int[] $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds = []): array
    {
        return [$this->itemStateUpdated];
    }

    /**
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function getItemStateUpdated(): StateMachineItemTransfer
    {
        return $this->itemStateUpdated;
    }
}
