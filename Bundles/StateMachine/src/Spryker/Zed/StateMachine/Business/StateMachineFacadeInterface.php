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
     * Specification:
     * - Must be triggered once per state machine when first item is added.
     * - Creates new process item in persistent storage if it does not exist.
     * - Creates new state item in persistent storage if it does not exist.
     * - Executes registered StateMachineHandlerInterface::getInitialStateForProcess() plugin.
     * - Executes registered StateMachineHandlerInterface::itemStateUpdated() plugin methods on state change.
     * - Persists state item history.
     * - Returns with the number of transitioned items.
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
     * Specification:
     * - State machine must be already initialized with StateMachineFacadeInterface::triggerForNewStateMachineItem().
     * - Triggers event for the provided item.
     * - Creates new state item in persistent storage if it does not exist.
     * - Executes registered StateMachineHandlerInterface::itemStateUpdated() plugin methods on state change.
     * - Persists state item history.
     * - Returns with the number of transitioned items.
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
     * Specification:
     * - State machine must be already initialized with StateMachineFacadeInterface::triggerForNewStateMachineItem().
     * - Triggers event for the provided items.
     * - Creates new state item in persistent storage if it does not exist.
     * - Executes registered StateMachineHandlerInterface::itemStateUpdated() plugin methods on state change.
     * - Persists state item history.
     * - Returns with the number of transitioned items.
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
     * Specification:
     * - Finds state machine handler by provided state machine name.
     * - Retrieves active process transfer list defined in handler by process name.
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
     * - Checks if state machine exists.
     *
     * @api
     *
     * @param string $stateMachineName
     *
     * @return bool
     */
    public function stateMachineExists($stateMachineName);

    /**
     * Specification:
     * - Gathers all transitions without any event for the provided state machine.
     * - Executes gathered transitions.
     *
     * @api
     *
     * @param string $stateMachineName
     *
     * @return int
     */
    public function checkConditions($stateMachineName);

    /**
     * Specification:
     * - Gathers all timeout expired events for the provided state machine.
     * - Executes gathered events.
     *
     * @api
     *
     * @param string $stateMachineName
     *
     * @return int
     */
    public function checkTimeouts($stateMachineName);

    /**
     * Specification:
     * - Loads state machine process from XML.
     * - Draws graph using graph library.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return string
     */
    public function drawProcess(StateMachineProcessTransfer $stateMachineProcessTransfer, $highlightState = null, $format = null, $fontSize = null);

    /**
     * Specification:
     * - Retrieves process id by provided process name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return int
     */
    public function getStateMachineProcessId(StateMachineProcessTransfer $stateMachineProcessTransfer);

    /**
     * Specification:
     * - Loads state machine process from XML using provided state machine item.
     * - Retrieves manual event list.
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
     * - Loads state machine process from XML using provided state machine item.
     * - Retrieves manual event list per items identifier.
     * - Items without any manual events are not part of result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return string[][]
     */
    public function getManualEventsForStateMachineItems(array $stateMachineItems);

    /**
     * Specification:
     * - Retrieves hydrated item transfer by provided item id and identifier pair.
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
     * - Retrieves hydrated item transfers by provided item id and identifier pairs.
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
     * - Retrieves state item history by state item identifier.
     *
     * @api
     *
     * @param int $idStateMachineProcess
     * @param int $identifier
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getStateHistoryByStateItemIdentifier($idStateMachineProcess, $identifier);

    /**
     * Specification:
     * - Loads state machine process from XML.
     * - Retrieves all items with state which have the provided flag.
     * - Traverse the item state history in the given sort (ASC or DESC), ASC by default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     * @param string $sort
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getItemsWithFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName, string $sort = 'ASC');

    /**
     * Specification:
     * - Loads state machine process from XML.
     * - Retrieves all items with state which have do not have the provided flag.
     * - Traverse the item state history in the given sort (ASC or DESC), ASC by default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineProcessTransfer $stateMachineProcessTransfer
     * @param string $flagName
     * @param string $sort
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    public function getItemsWithoutFlag(StateMachineProcessTransfer $stateMachineProcessTransfer, $flagName, string $sort = 'ASC');

    /**
     * Specification:
     * - Clears all expired item locks.
     *
     * @api
     *
     * @return void
     */
    public function clearLocks();
}
