<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\StateMachine\Business;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;

/**
 * @method \Spryker\Zed\StateMachine\Business\StateMachineBusinessFactory getFactory()
 */
interface StateMachineFacadeInterface
{

    /**
     * Trigger when first time adding item to state machine process
     *
     * Specification:
     *  - Returns boolean true if trigger was successful
     *  - Creates new process item in persistence if does not exist
     *  - Creates new state item in persistence if does not exist
     *  - Calls plugin method in StateMachineHandlerInterface::getInitialStateForProcess
     *  - Calls plugin method in StateMachineHandlerInterface::itemStateUpdated when state changed happens
     *  - Persist state history
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param int $identifier - this is id of foreign entity you want to track in state machine, it's stored in history table.
     *
     * @return int
     */
    public function triggerForNewStateMachineItem(StateMachineProcessTransfer $stateMachineProcessTransfer, $identifier);

    /**
     * Trigger event for single item. Must be already initialized with triggerForNewStateMachineItem
     *
     * Specification:
     *  - Returns boolean true if trigger was successful
     *  - Creates new state item in persistence if does not exist
     *  - Calls plugin method in StateMachineHandlerInterface::itemStateUpdated when state changed happens
     *  - Persist state history
     *
     * @api
     *
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return int
     */
    public function triggerEvent($eventName, StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * Trigger event for multiple items. Must be already initialized with triggerForNewStateMachineItem
     *
     * Specification:
     *  - Returns boolean true if trigger was successful
     *  - Creates new state item in persistence if does not exist
     *  - Calls plugin method in StateMachineHandlerInterface::itemStateUpdated when state changed happens
     *  - Persist state history
     *
     * @api
     *
     * @param string $eventName
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return int
     */
    public function triggerEventForItems($eventName, array $stateMachineItems);

    /**
     * Return available state machine process list. Includes all state machine details: states transitions, events
     *
     * Specification:
     *  - Parse xml file and build object graph from it.
     *  - Calls plugin method in StateMachineHandlerInterface::getActiveProcesses to get list of process
     *
     * @api
     *
     * @param string $stateMachineName
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer[]
     */
    public function getProcesses($stateMachineName);

    /**
     * Specification:
     *  - Read all transition without event for given state machine.
     *  - Read from database items with those transitions
     *  - execute each transition
     *
     * @api
     *
     * @param string $stateMachineName
     *
     * @return int
     */
    public function checkConditions($stateMachineName);

    /**
     *
     *  Specification:
     *  - Read all expired timeout events for given state machine
     *  - Execute events
     *
     * @api
     *
     * @param string $stateMachineName
     *
     * @return int
     */
    public function checkTimeouts($stateMachineName);

    /**
     *
     * Specification:
     *  - Read all processes from given state machine
     *  - Using graph library draw graph
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $highlightState
     * @param string $format
     * @param int $fontSize
     *
     * @return string
     */
    public function drawProcess(StateMachineProcessTransfer $stateMachineProcessTransfer, $highlightState = null, $format = null, $fontSize = null);

    /**
     * Specification:
     *  - Read process id from database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return int
     */
    public function getStateMachineProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer);

    /**
     *
     *  Specification:
     *  - Read state machine process list
     *  - Find all manual events
     *  - Map manual events with give event name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return string[]
     */
    public function getManualEventsForStateMachineItem(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * Specification:
     *  - Read state machine process list
     *  - Find all manual events
     *  - Map manual events with give event state machine items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return string[]
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems);

    /**
     *
     * Specification:
     *  - Get StateMachine item transfer as stored in persistence
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    public function getProcessedStateMachineItemTransfer(StateMachineItemTransfer $stateMachineItemTransfer);

    /**
     * Specification:
     *  - Get StateMachine items transfer as stored in persistence
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getProcessedStateMachineItems(array $stateMachineItems);

    /**
     * Specification:
     *  - Get history for given state machine item reading state machine history table
     *
     * @api
     *
     * @param int $idStateMachineProcess
     * @param int $identifier
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($idStateMachineProcess, $identifier);

    /**
     * Specification:
     *  - Find all items with flag for given state machine and process, parse xml.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName);

    /**
     * Specification:
     *  - Find all items without flag for given state machine and process, parse xml.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName);

    /**
     * Specification:
     *  - Clear all expired locks from database, deletes rows.
     *
     * @api
     *
     * @return void
     */
    public function clearLocks();

}
