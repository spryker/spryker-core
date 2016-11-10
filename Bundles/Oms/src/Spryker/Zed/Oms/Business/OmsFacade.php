<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Oms\Business\OmsBusinessFactory getFactory()
 */
class OmsFacade extends AbstractFacade implements OmsFacadeInterface
{

    /**
     *
     * Specification:
     *  - Reads all manual event for given order.
     *  - Returns list of manuals events
     *
     * @api
     *
     * @param int $idOrderItem
     *
     * @return string[]
     */
    public function getManualEvents($idOrderItem)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getManualEvents($idOrderItem);
    }

    /**
     *
     * Specification:
     *  - Checks if any order items is in state with givent flag
     *  - Returns true if order item have flag in state
     *
     * @api
     *
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlagged($idOrder, $flag)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->isOrderFlagged($idOrder, $flag);
    }

    /**
     *
     * Specification:
     *  - Checks if all order items is in state with givent flag
     *  - Returns true if all order items have flag in state
     *
     * @api
     *
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlaggedAll($idOrder, $flag)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->isOrderFlaggedAll($idOrder, $flag);
    }

    /**
     *
     * Specification:
     *  - Trigers even for given order items, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *
     * @api
     *
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, array $data = [])
    {
        return $this->getFactory()
            ->createLockedOrderStateMachine()
            ->triggerEventForOrderItems($eventId, $orderItemIds, $data);
    }

    /**
     *
     * Specification:
     *  - Trigers even for given order items, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Initialises correct state machine, sets initial state
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *
     * @api
     *
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data = [])
    {
        return $this->getFactory()
            ->createLockedOrderStateMachine()
            ->triggerEventForNewOrderItems($orderItemIds, $data);
    }

    /**
     *
     * Specification:
     *  - Trigers even for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *
     * @api
     *
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, array $data = [])
    {
        return $this->getFactory()
            ->createLockedOrderStateMachine()
            ->triggerEventForOneOrderItem($eventId, $orderItemId, $data);
    }

    /**
     *
     * Specification:
     *  - Reads all active state machine processes, which defined in spryker configuration OmsConstants::ACTIVE_PROCESSES
     *  - Returns array of Process objects
     *
     * @api
     *
     * @return \Spryker\Zed\Oms\Business\Process\Process[]
     */
    public function getProcesses()
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getProcesses();
    }

    /**
     *
     * Specification:
     *  - Returns list of active processes, which defined in spryker configuration OmsConstants::ACTIVE_PROCESSES
     *
     * @api
     *
     * @return array
     */
    public function getProcessList()
    {
        return $this->getFactory()
            ->getConfig()
            ->getActiveProcesses();
    }

    /**
     *
     * Specification:
     *  - Reads all transitions without event.
     *  - Reads from database items with those transitions
     *  - Executes each transition
     *  - Returns number of affected items
     *
     * @api
     *
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext = [])
    {
        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->checkConditions();
    }

    /**
     *
     * Specification:
     *  - Reads all expired timeout events
     *  - Execute events
     *  - Returns number of affected items
     *
     * @api
     *
     * @param array $logContext
     *
     * @return int
     */
    public function checkTimeouts(array $logContext = [])
    {
        $orderStateMachine = $this->getFactory()
            ->createLockedOrderStateMachine($logContext);

        return $this->getFactory()
            ->createOrderStateMachineTimeout()
            ->checkTimeouts($orderStateMachine);
    }

    /**
     *
     * Specification:
     *  - Draws state machine process using internal graphic library
     *  - Returns html to display in presentation
     *
     * @api
     *
     * @param string $processName
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return string
     */
    public function drawProcess($processName, $highlightState = null, $format = null, $fontSize = null)
    {
        $process = $this->getFactory()
            ->createOrderStateMachineBuilder()
            ->createProcess($processName);

        return $process->draw($highlightState, $format, $fontSize);
    }

    /**
     *
     * Specification:
     *  - Gets all events for order item with source state having manual event
     *  - Gets all events for whole order state having manual event
     *  - Returns array of order manual events
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return \Spryker\Zed\Oms\Business\Process\Event[]
     */
    public function getGroupedManuallyExecutableEvents(SpySalesOrder $order)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getGroupedManuallyExecutableEvents($order);
    }

    /**
     *
     * Specification:
     *  - Gets all order items which have state with given flag
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getItemsWithFlag($order, $flag);
    }

    /**
     *
     * Specification:
     *  - Gets all order items which does not have state with given flag
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getItemsWithoutFlag(SpySalesOrder $order, $flag)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getItemsWithoutFlag($order, $flag);
    }

    /**
     *
     * Specification:
     *  - Reads all logged state machine operations for givent order
     *  - Returns TransitionLog entity list
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param array $logContext
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLog[]
     */
    public function getLogForOrder(SpySalesOrder $order, array $logContext = [])
    {
        // FIXME Ticket core-119
        return $this->getFactory()
            ->createUtilTransitionLog($logContext)
            ->getLogForOrder($order);
    }

    /**
     *
     * Specification:
     *  - Reads all order items having state with reserved attribute for given concrete product
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getReservedOrderItemsForSku($sku)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getReservedOrderItemsForSku($sku);
    }

    /**
     *
     * Specification:
     *  - Counts orders with items with given sku which are in state with flag reserved
     *
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku)
    {
        return $this->getFactory()
            ->createUtilReservation()
            ->sumReservedProductQuantitiesForSku($sku);
    }

    /**
     *
     * Specification:
     *  - Gets state entity by state name from persistence
     *
     * @api
     *
     * @param string $stateName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getStateEntity($stateName)
    {
        return $this->getFactory()
            ->createOrderStateMachinePersistenceManager()
            ->getStateEntity($stateName);
    }

    /**
     *
     * Specification:
     *  - Gets process entity by process name from persistence
     *
     * @api
     *
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function getProcessEntity($processName)
    {
        return $this->getFactory()
            ->createOrderStateMachinePersistenceManager()
            ->getProcessEntity($processName);
    }

    /**
     *
     * Specification:
     *  - Gets inital state entity for new order state machine process
     *  - Inital state name is set in OmsConstants
     *
     * @api
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getInitialStateEntity()
    {
        return $this->getFactory()
            ->createOrderStateMachinePersistenceManager()
            ->getInitialStateEntity();
    }

    /**
     *
     * Specification:
     *  - Gets current state machine process for give order item
     *  - Reads state display name from xml definition
     *  - Returns display name
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return string
     */
    public function getStateDisplayName(SpySalesOrderItem $orderItem)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getStateDisplayName($orderItem);
    }

    /**
     *
     * Specification:
     *  - Trigers even for given order items, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *
     * @api
     *
     * @param string $eventId
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        $orderItemsArray = $orderItems->getData();

        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     *
     * Specification:
     *  - Trigers even for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewItem(ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        $orderItemsArray = $orderItems->getData();

        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->triggerEventForNewItem($orderItemsArray, $data);
    }

    /**
     *
     * Specification:
     *  - Trigers even for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *
     * @api
     *
     * @param string $eventId
     * @param \Generated\Shared\Transfer\OrderTransfer $orderItem
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOneItem($eventId, $orderItem, array $logContext, array $data = [])
    {
        $orderItemsArray = [$orderItem];

        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     *
     * Specification:
     *  - Reads all order states
     *  - Counts orders in each state and puts into corresponding state
     *  - Return matrix
     *
     * @api
     *
     * @return array
     */
    public function getOrderItemMatrix()
    {
        return $this->getFactory()->createUtilOrderItemMatrix()->getMatrix();
    }

    /**
     *
     * Specification:
     *  - Reads all order states
     *  - Counts orders in each state and puts into corresponding state
     *  - Return matrix
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface[]
     */
    public function getManualEventsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     *
     * Specification:
     *  - Read all order manual event from persistence
     *  - Return array of manual events
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getDistinctManualEventsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getDistinctManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     *
     * Specification:
     *  - Clear state machine lock table, which used when items are locked. This is garbage collection call
     *
     * @api
     *
     * @return void
     */
    public function clearLocks()
    {
        $this->getFactory()->createTriggerLocker()->clearLocks();
    }

}
