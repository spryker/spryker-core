<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;

interface OmsFacadeInterface
{
    /**
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
    public function getManualEvents($idOrderItem);

    /**
     * Specification:
     *  - Checks if order item is in state with givent flag
     *  - Returns true if current state have flag
     *
     * @api
     *
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlagged($idOrder, $flag);

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
    public function isOrderFlaggedAll($idOrder, $flag);

    /**
     * Specification:
     *  - Checks if all order items are flagged to exclude order from customer.
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return bool
     */
    public function isOrderFlaggedExcludeFromCustomer($idOrder);

    /**
     * Specification:
     *  - Triggers even for given order items, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, array $data = []);

    /**
     * Specification:
     *  - Triggers even for given order items, data is used as additional payload which is passed to commands.
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
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data = []);

    /**
     * Specification:
     *  - Triggers even for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, array $data = []);

    /**
     * Specification:
     *  - Reads all active state machine processes, which defined in spryker configuration OmsConstants::ACTIVE_PROCESSES
     *  - Returns array of Process objects
     *
     * @api
     *
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface[]
     */
    public function getProcesses();

    /**
     * Specification:
     *  - Returns list of active processes, which defined in spryker configuration OmsConstants::ACTIVE_PROCESSES
     *
     * @api
     *
     * @return array
     */
    public function getProcessList();

    /**
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
    public function checkConditions(array $logContext = []);

    /**
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
    public function checkTimeouts(array $logContext = []);

    /**
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
    public function drawProcess($processName, $highlightState = null, $format = null, $fontSize = null);

    /**
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
    public function getGroupedManuallyExecutableEvents(SpySalesOrder $order);

    /**
     *  Specification:
     *  - Gets all order items which have state with given flag
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag);

    /**
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
    public function getItemsWithoutFlag(SpySalesOrder $order, $flag);

    /**
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
    public function getLogForOrder(SpySalesOrder $order, array $logContext = []);

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
    public function getReservedOrderItemsForSku($sku);

    /**
     * Specification:
     *  - Count orders with items with given sku which are in state with flag reserved
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku, ?StoreTransfer $storeTransfer = null);

    /**
     * Specification:
     *  - Returns reserved quantity for the given sku which aggregated in OMS
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku, StoreTransfer $storeTransfer);

    /**
     * Specification:
     *  - Gets state entity by state name from persistence
     *
     * @api
     *
     * @param string $stateName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getStateEntity($stateName);

    /**
     * Specification:
     *  - Gets process entity by process name from persistence
     *
     * @api
     *
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function getProcessEntity($processName);

    /**
     * Specification:
     *  - Gets initial state entity for new order state machine process
     *  - Initial state name is set in OmsConstants
     *
     * @api
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getInitialStateEntity();

    /**
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
    public function getStateDisplayName(SpySalesOrderItem $orderItem);

    /**
     * Specification:
     *  - Triggers even for given order items, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param string $eventId
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = []);

    /**
     * Specification:
     *  - Triggers even for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForNewItem(ObjectCollection $orderItems, array $logContext, array $data = []);

    /**
     * Specification:
     *  - Triggers even for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Unlocks state machine trigger
     *  - Returns data which was aggregated from state machine plugins
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param string $eventId
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param array $logContext
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForOneItem($eventId, $orderItem, array $logContext, array $data = []);

    /**
     * Specification:
     *  - Reads all order states
     *  - Counts orders in each state and puts into corresponding state
     *  - Return matrix
     *
     * @api
     *
     * @return array
     */
    public function getOrderItemMatrix();

    /**
     * Specification:
     *  - Reads all order manual event from persistence
     *  - Returns array of manual events
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface[]
     */
    public function getManualEventsByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     *  - Reads all order manual event from persistence
     *  - Returns array of manual events
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getDistinctManualEventsByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     *  - Clears state machine lock table, which used when items are locked. This is garbage collection call
     *
     * @api
     *
     * @return void
     */
    public function clearLocks();

    /**
     * Specification:
     * - Sends the order confirmation mail
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderConfirmationMail(SpySalesOrder $salesOrderEntity);

    /**
     * Specification:
     * - Sends the order shipped mail
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderShippedMail(SpySalesOrder $salesOrderEntity);

    /**
     * Specification:
     *  - Handles stores stock reservation in reservation version table
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function saveReservationVersion($sku);

    /**
     * Specification:
     *  - Writes reservation from other store to synchronize it.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function importReservation(
        OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
    );

    /**
     * Specification:
     *   - Reader non exported reservations and run through Reservation export plugins
     *
     * @api
     *
     * @return void
     */
    public function exportReservation();

    /**
     * Specification:
     *  - Reads reservation about from as it was set from other stores when stock is shared
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getReservationsFromOtherStores($sku, StoreTransfer $storeTransfer);

    /**
     * Specification:
     *  - Returns last exported reservation version when exporting to external stores
     *
     * @api
     *
     * @return int
     */
    public function getLastExportedReservationVersion();
}
