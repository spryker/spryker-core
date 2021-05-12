<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Dependency\Plugin;

use Generated\Shared\Transfer\StateMachineItemTransfer;

interface StateMachineHandlerInterface
{
    /**
     * Specification:
     * - List of command plugins for this state machine for all processes. Array key is identifier in SM XML file.
     *
     * [
     *   'Command/Plugin' => new Command(),
     *   'Command/Plugin2' => new Command2(),
     * ]
     *
     * @api
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface[]
     */
    public function getCommandPlugins();

    /**
     * Specification:
     * - List of condition plugins for this state machine for all processes. Array key is identifier in SM XML file.
     *
     *  [
     *   'Condition/Plugin' => new Condition(),
     *   'Condition/Plugin2' => new Condition2(),
     * ]
     *
     * @api
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface[]
     */
    public function getConditionPlugins();

    /**
     * Specification:
     * - Name of state machine used by this handler.
     *
     * @api
     *
     * @return string
     */
    public function getStateMachineName();

    /**
     * Specification:
     * - List of active processes used for this state machine.
     *
     * [
     *   'ProcessName',
     *   'ProcessName2 ,
     * ]
     *
     * @api
     *
     * @return string[]
     */
    public function getActiveProcesses();

    /**
     * Specification:
     * - Provide initial state name for item when state machine initialized. Using process name.
     *
     * @api
     *
     * @param string $processName
     *
     * @return string
     */
    public function getInitialStateForProcess($processName);

    /**
     * Specification:
     * - This method is called when state of item was changed, client can create custom logic for example update it's related table with new stateId and processId.
     * - StateMachineItemTransfer:identifier is id of entity from client.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function itemStateUpdated(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * Specification:
     * - This method should return all list of StateMachineItemTransfer, with (identifier, IdStateMachineProcess, IdItemState)
     *
     * @api
     *
     * @param int[] $stateIds
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateMachineItemsByStateIds(array $stateIds = []);
}
