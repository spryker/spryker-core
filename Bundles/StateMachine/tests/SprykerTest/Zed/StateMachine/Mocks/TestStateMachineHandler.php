<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Mocks;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

class TestStateMachineHandler implements StateMachineHandlerInterface
{
    /**
     * @var \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected $itemStateUpdated;

    /**
     * @var \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    protected $stateMachineItemsByStateIds;

    /**
     * List of command plugins for this state machine for all processes.
     *
     * @return array
     */
    public function getCommandPlugins()
    {
        return [
            'Test/CreateInvoice' => new TestCommandPlugin(),
            'Test/SendInvoice' => new TestCommandPlugin(),
        ];
    }

    /**
     * List of condition plugins for this state machine for all processes.
     *
     * @return array
     */
    public function getConditionPlugins()
    {
        return [
            'Test/IsInvoiceSent' => new TestConditionPlugin(),
            'Test/Condition' => new TestConditionPlugin(),
        ];
    }

    /**
     * Name of state machine used by this handler.
     *
     * @return string
     */
    public function getStateMachineName()
    {
        return 'TestingSm';
    }

    /**
     * List of active processes used for this state machine
     *
     * @return string[]
     */
    public function getActiveProcesses()
    {
        return [
          'TestProcess',
        ];
    }

    /**
     * Provide initial state name for item when state machine initialized. Useing proces name.
     *
     * @param string $processName
     *
     * @return string
     */
    public function getInitialStateForProcess($processName)
    {
        return 'new';
    }

    /**
     * This method is called when state of item was changed, client can create custom logic for example update it's related table with new state id/name.
     * StateMachineItemTransfer:identifier is id of entity from implementor.
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function itemStateUpdated(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $this->itemStateUpdated = $stateMachineItemTransfer;
    }

    /**
     * This method should return all item identifiers which are in passed state ids.
     *
     * @param array $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds = [])
    {
        $result = [];
        foreach ($this->stateMachineItemsByStateIds as $stateMachineItemTransfer) {
            if (in_array($stateMachineItemTransfer->getIdItemState(), $stateIds)) {
                $result[] = $stateMachineItemTransfer;
            }
        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function getItemStateUpdated()
    {
        return $this->itemStateUpdated;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemsByStateIds
     *
     * @return void
     */
    public function setStateMachineItemsByStateIds(array $stateMachineItemsByStateIds)
    {
        $this->stateMachineItemsByStateIds = $stateMachineItemsByStateIds;
    }
}
