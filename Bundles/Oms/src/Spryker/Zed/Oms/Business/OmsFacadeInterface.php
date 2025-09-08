<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer;
use Generated\Shared\Transfer\OmsEventTimeoutCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\OmsEventTimeoutCollectionResponseTransfer;
use Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionResponseTransfer;
use Generated\Shared\Transfer\OmsOrderItemStateTransfer;
use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\OmsTransitionLogCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\OmsTransitionLogCollectionResponseTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;
use Generated\Shared\Transfer\OrderMatrixCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\DecimalObject\Decimal;

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
     * @return array<string>
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
     *  - Triggers event for given order items, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Notified listeners about event handling
     *  - Unlocks state machine trigger
     *  - Returns an array with data aggregated from the state machine plugins and an `OmsEventTriggerResponseTransfer` by the key `\Spryker\Zed\Oms\OmsConfig::OMS_EVENT_TRIGGER_RESPONSE`.
     *  - If command plugins execution ends without issues `OmsEventTriggerResponse.isSuccessful = true`.
     *  - In case of any error in command plugin `OmsEventTriggerResponse.isSuccessful = false` and `OmsEventTriggerResponse.messages` contains the errors description.
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param string $eventId
     * @param array $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, array $data = []);

    /**
     * Specification:
     *  - Triggers event for given order items, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Initialises correct state machine, sets initial state
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Notified listeners about event handling
     *  - Unlocks state machine trigger
     *  - Returns an array with data aggregated from the state machine plugins and an `OmsEventTriggerResponseTransfer` by the key `\Spryker\Zed\Oms\OmsConfig::OMS_EVENT_TRIGGER_RESPONSE`.
     *  - If command plugins execution ends without issues `OmsEventTriggerResponse.isSuccessful = true`.
     *  - In case of any error in command plugin `OmsEventTriggerResponse.isSuccessful = false` and `OmsEventTriggerResponse.messages` contains the errors description.
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param array $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data = []);

    /**
     * Specification:
     *  - Triggers event for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Notified listeners about event handling
     *  - Unlocks state machine trigger
     *  - Returns an array with data aggregated from the state machine plugins and an `OmsEventTriggerResponseTransfer` by the key `\Spryker\Zed\Oms\OmsConfig::OMS_EVENT_TRIGGER_RESPONSE`.
     *  - If command plugins execution ends without issues `OmsEventTriggerResponse.isSuccessful = true`.
     *  - In case of any error in command plugin `OmsEventTriggerResponse.isSuccessful = false` and `OmsEventTriggerResponse.messages` contains the errors description.
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param string $eventId
     * @param int $orderItemId
     * @param array<string, mixed> $data
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
     * @param bool $regenerateCache
     *
     * @return array<\Spryker\Zed\Oms\Business\Process\ProcessInterface>
     */
    public function getProcesses(bool $regenerateCache = false);

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
     *  - Reads from database items with those transitions.
     *  - Executes each transition.
     *  - Returns number of affected items.
     *  - OmsCheckConditionsQueryCriteriaTransfer::$storeName parameter filters the order items by the given store name.
     *  - OmsCheckConditionsQueryCriteriaTransfer::$limit parameter filters the number of order items to be processed by the given limit of orders.
     *
     * @api
     *
     * @param array $logContext
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @return int
     */
    public function checkConditions(array $logContext = [], ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer = null);

    /**
     * Specification:
     *  - Reads all expired timeout events.
     *  - Execute events.
     *  - Returns number of affected items.
     *  - OmsCheckConditionsQueryCriteriaTransfer::$storeName parameter filters the order items by the given store name.
     *  - OmsCheckConditionsQueryCriteriaTransfer::$limit parameter filters the number of order items to be processed by the given limit of orders.
     *
     * @api
     *
     * @param array $logContext
     * @param \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer|null $omsCheckTimeoutsQueryCriteriaTransfer
     *
     * @return int
     */
    public function checkTimeouts(array $logContext = [], ?OmsCheckTimeoutsQueryCriteriaTransfer $omsCheckTimeoutsQueryCriteriaTransfer = null);

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
     * @return array<\Spryker\Zed\Oms\Business\Process\Event>
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
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
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
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    public function getItemsWithoutFlag(SpySalesOrder $order, $flag);

    /**
     * Specification:
     *  - Reads all logged state machine operations for givent order
     *  - Returns TransitionLog entity list
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param array $logContext
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Oms\Persistence\SpyOmsTransitionLog>
     */
    public function getLogForOrder(SpySalesOrder $order, array $logContext = []);

    /**
     * Specification:
     *  - Count orders with items with given sku which are in state with flag reserved
     *
     * @api
     *
     * @deprecated Not used anymore. Will be removed with next major release.
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function sumReservedProductQuantitiesForSku(string $sku, ?StoreTransfer $storeTransfer = null): Decimal;

    /**
     * Specification:
     *  - Returns reserved quantity for the given sku which aggregated in OMS.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getOmsReservedProductQuantityForSku(string $sku, StoreTransfer $storeTransfer): Decimal;

    /**
     * Specification:
     *  - Returns reserved quantity summarized for the given skus which aggregated in OMS.
     *
     * @api
     *
     * @param array<string> $skus
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getOmsReservedProductQuantityForSkus(array $skus, StoreTransfer $storeTransfer): Decimal;

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
     *  - Triggers event for given order items, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Notified listeners about event handling
     *  - Unlocks state machine trigger
     *  - Returns an array with data aggregated from the state machine plugins and an `OmsEventTriggerResponseTransfer` by the key `\Spryker\Zed\Oms\OmsConfig::OMS_EVENT_TRIGGER_RESPONSE`.
     *  - If command plugins execution ends without issues `OmsEventTriggerResponse.isSuccessful = true`.
     *  - In case of any error in command plugin `OmsEventTriggerResponse.isSuccessful = false` and `OmsEventTriggerResponse.messages` contains the errors description.
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param string $eventId
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = []);

    /**
     * Specification:
     *  - Triggers event for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Notified listeners about event handling
     *  - Unlocks state machine trigger
     *  - Returns an array with data aggregated from the state machine plugins and an `OmsEventTriggerResponseTransfer` by the key `\Spryker\Zed\Oms\OmsConfig::OMS_EVENT_TRIGGER_RESPONSE`.
     *  - If command plugins execution ends without issues `OmsEventTriggerResponse.isSuccessful = true`.
     *  - In case of any error in command plugin `OmsEventTriggerResponse.isSuccessful = false` and `OmsEventTriggerResponse.messages` contains the errors description.
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForNewItem(ObjectCollection $orderItems, array $logContext, array $data = []);

    /**
     * Specification:
     *  - Triggers event for given order item, data is used as additional payload which is passed to commands.
     *  - Locks state machine trigger from concurrent access
     *  - Logs state machine transitions
     *  - Executes state machine for each order item following their definitions
     *  - Calls command plugins
     *  - Calls condition plugins
     *  - Sets timeouts for timeout events
     *  - Triggers item reservation plugins
     *  - Notified listeners about event handling
     *  - Unlocks state machine trigger
     *  - Returns an array with data aggregated from the state machine plugins and an `OmsEventTriggerResponseTransfer` by the key `\Spryker\Zed\Oms\OmsConfig::OMS_EVENT_TRIGGER_RESPONSE`.
     *  - If command plugins execution ends without issues `OmsEventTriggerResponse.isSuccessful = true`.
     *  - In case of any error in command plugin `OmsEventTriggerResponse.isSuccessful = false` and `OmsEventTriggerResponse.messages` contains the errors description.
     *  - Returns NULL is case of an internal failure
     *
     * @api
     *
     * @param string $eventId
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param array $logContext
     * @param array<string, mixed> $data
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
     * @deprecated Use {@link \Spryker\Zed\Oms\Business\OmsFacadeInterface::getOrderMatrixCollection()} instead.
     *
     * @return array
     */
    public function getOrderItemMatrix();

    /**
     * Specification:
     * - Retrieves a batch of matrix order items based on provided criteria filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderMatrixCollectionTransfer
     */
    public function getOrderMatrixCollection(OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer): OrderMatrixCollectionTransfer;

    /**
     * Specification:
     * - Retrieves all active processes from the database.
     *
     * @api
     *
     * @return array<int, string>
     */
    public function getProcessNamesIndexedByIdOmsOrderProcess(): array;

    /**
     * Specification:
     *  - Reads all order manual event from persistence
     *  - Returns array of manual events
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array<array<string>>
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
     * @return array<string>
     */
    public function getDistinctManualEventsByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     *  - Reads all order manual events from persistence.
     *  - Returns a list of grouped manual events.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array<string>
     */
    public function getGroupedDistinctManualEventsByIdSalesOrder(int $idSalesOrder): array;

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
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return void
     */
    public function saveReservationVersion($sku, ?StoreTransfer $storeTransfer = null);

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
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getReservationsFromOtherStores(string $sku, StoreTransfer $storeTransfer): Decimal;

    /**
     * Specification:
     *  - Returns last exported reservation version when exporting to external stores
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return int
     */
    public function getLastExportedReservationVersion();

    /**
     * Specification:
     *  - Reads state flags from XML definition
     *  - Returns a list of state flags
     *
     * @api
     *
     * @param string $processName
     * @param string $stateName
     *
     * @return array<string>
     */
    public function getStateFlags(string $processName, string $stateName): array;

    /**
     * Specification:
     *  - Saves OMS Reservation for a given sku, store and quantity.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\DecimalObject\Decimal $reservationQuantity
     *
     * @return void
     */
    public function saveReservation(string $sku, StoreTransfer $storeTransfer, Decimal $reservationQuantity): void;

    /**
     * Specification:
     *  - Updates reservation quantity for a given sku.
     *
     * @api
     *
     * @deprecated Use {@link updateReservation()} instead.
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity(string $sku): void;

    /**
     * Specification:
     *  - Updates reservation quantity for different entities from a given ReservationRequest.
     *  - Calculates total current reservation for given ReservationRequestTransfer by executing the OmsReservationAggregationPluginInterface plugin stack and adding their sum amount.
     *  - Uses original reservation aggregation if no plugin returns an aggregation of reservations.
     *  - Uses `OmsReservationWriterStrategyPluginInterface` stack to save reservation entity.
     *  - Checks if reservation for ReservationRequest already exists, if so it updates it with new values, otherwise creates a new reservation entity.
     *  - Does the same writing procedure for stores with shared persistence based on configuration.
     *  - Runs a stack of ReservationPostSaveTerminationAwareStrategyPluginInterface plugins after saving reservation which terminates the execution of remaining plugins if one plugin returns isTerminated to be true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function updateReservation(ReservationRequestTransfer $reservationRequestTransfer): void;

    /**
     * Specification:
     *  - Reads states from XML definition
     *  - Returns a list of reserved state objects in an associative array
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return \Generated\Shared\Transfer\OmsStateCollectionTransfer
     */
    public function getOmsReservedStateCollection(): OmsStateCollectionTransfer;

    /**
     * Specification:
     * - Hydrates history states for given order items.
     * - Copies createAt field from latest history state to ItemTransfer::state.
     * - Sets ItemTransfer::stateHistory.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithStateHistory(array $itemTransfers): array;

    /**
     * Specification:
     * - Expands order with OMS unique states from order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithOmsStates(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Reads order items from persistence using criteria from filter.
     * - Returns available manual events for found order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return array<array<string>>
     */
    public function getOrderItemManualEvents(OrderItemFilterTransfer $orderItemFilterTransfer): array;

    /**
     * Specification:
     * - Returns reserved quantity for provided ReservationRequest.
     * - Runs a stack of `OmsReservationReaderStrategyPluginInterface` plugins to get reservation quantity.
     * - Gets original reservation quantity if no one plugin is applicable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getOmsReservedProductQuantity(ReservationRequestTransfer $reservationRequestTransfer): ReservationResponseTransfer;

    /**
     * Specification:
     * - Reads order items from persistence.
     * - Gets the current state machine process for each order item.
     * - Reads state display name from XML definition.
     * - Expands order items with item state.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithItemState(array $itemTransfers): array;

    /**
     * Specification:
     * - Reads order items from persistence.
     * - Gets the current state machine process for each order item.
     * - Reads state display name from XML definition.
     * - Expands orders with aggregated item states.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function expandOrdersWithAggregatedItemStates(array $orderTransfers): array;

    /**
     * Specification:
     * - Checks for cancellable flag for each order item.
     * - If all items are applicable for cancel, sets `Order::isCancellable=true`, false otherwise.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function setOrderIsCancellableByItemState(array $orderTransfers): array;

    /**
     * Specification:
     * - Sends OrderStatusChanged message to the message broker `orders` channel.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function sendOrderStatusChangedMessage(int $idSalesOrder): void;

    /**
     * Specification:
     * - Expects `OrderTransfer.orderReference` to be set.
     * - Requires `OrderTransfer.idSalesOrder` to be set if `OrderTransfer.orderReference` is not provided.
     * - Reads order items from Persistence.
     * - Checks if all order items are satisfied by provided flag.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $flag
     *
     * @return bool
     */
    public function areOrderItemsSatisfiedByFlag(OrderTransfer $orderTransfer, string $flag): bool;

    /**
     * Specification:
     * - Uses `OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter OMS order item state history entities by the sales order item IDs.
     * - Deletes found by criteria OMS order item state history entities.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer $omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionResponseTransfer
     */
    public function deleteOmsOrderItemStateHistoryCollection(
        OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer $omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer
    ): OmsOrderItemStateHistoryCollectionResponseTransfer;

    /**
     * Specification:
     * - Uses `OmsTransitionLogCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter OMS transition log entities by the sales order item IDs.
     * - Deletes found by criteria OMS transition log entities.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsTransitionLogCollectionDeleteCriteriaTransfer $omsTransitionLogCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsTransitionLogCollectionResponseTransfer
     */
    public function deleteOmsTransitionLogCollection(
        OmsTransitionLogCollectionDeleteCriteriaTransfer $omsTransitionLogCollectionDeleteCriteriaTransfer
    ): OmsTransitionLogCollectionResponseTransfer;

    /**
     * Specification:
     * - Uses `OmsEventTimeoutCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter OMS event timeout entities by the sales order item IDs.
     * - Deletes found by criteria OMS event timeout entities.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsEventTimeoutCollectionDeleteCriteriaTransfer $omsEventTimeoutCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsEventTimeoutCollectionResponseTransfer
     */
    public function deleteOmsEventTimeoutCollection(
        OmsEventTimeoutCollectionDeleteCriteriaTransfer $omsEventTimeoutCollectionDeleteCriteriaTransfer
    ): OmsEventTimeoutCollectionResponseTransfer;

    /**
     * Specification:
     * - Gets oms order item state by state name from persistence.
     * - Creates a new entity if it does not exist.
     *
     * @api
     *
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateTransfer
     */
    public function getOmsOrderItemState(string $stateName): OmsOrderItemStateTransfer;

    /**
     * Specification:
     * - Acquires a lock for the order item based on the provided identifier.
     * - If blocking is true, it will wait until the lock can be acquired.
     * - If blocking is false, it will return false immediately if the lock cannot be acquired.
     *
     * @api
     *
     * @param array|string $identifier
     * @param bool $blocking
     *
     * @return bool
     */
    public function acquireOrderItemLock(array|string $identifier, bool $blocking): bool;

    /**
     * Specification:
     * - Release a lock for the order item based on the provided identifier.
     *
     * @api
     *
     * @param array|string $identifier
     *
     * @return void
     */
    public function releaseOrderItemLock(array|string $identifier): void;

    /**
     * Specification:
     * - Acquires a lock for the order based on the provided identifier.
     * - If blocking is true, it will wait until the lock can be acquired.
     * - If blocking is false, it will return false immediately if the lock cannot be acquired.
     *
     * @api
     *
     * @param array|string $identifier
     * @param bool $blocking
     *
     * @return bool
     */
    public function acquireOrderLock(array|string $identifier, bool $blocking): bool;

    /**
     * Specification:
     * - Release a lock for the order based on the provided identifier.
     *
     * @api
     *
     * @param array|string $identifier
     *
     * @return void
     */
    public function releaseOrderLock(array|string $identifier): void;
}
