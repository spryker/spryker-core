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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Oms\Business\OmsBusinessFactory getFactory()
 * @method \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface getRepository()
 * @method \Spryker\Zed\Oms\Persistence\OmsEntityManagerInterface getEntityManager()
 */
class OmsFacade extends AbstractFacade implements OmsFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOrderItem
     *
     * @return array<string>
     */
    public function getManualEvents($idOrderItem)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getManualEvents($idOrderItem);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return bool
     */
    public function isOrderFlaggedExcludeFromCustomer($idOrder)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->isOrderFlaggedExcludeFromCustomer($idOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $eventId
     * @param array $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, array $data = [])
    {
        return $this->getFactory()
            ->createLockedOrderStateMachine()
            ->triggerEventForOrderItems($eventId, $orderItemIds, $data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data = [])
    {
        return $this->getFactory()
            ->createLockedOrderStateMachine()
            ->triggerEventForNewOrderItems($orderItemIds, $data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $eventId
     * @param int $orderItemId
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, array $data = [])
    {
        return $this->getFactory()
            ->createLockedOrderStateMachine()
            ->triggerEventForOneOrderItem($eventId, $orderItemId, $data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param bool $regenerateCache
     *
     * @return array<\Spryker\Zed\Oms\Business\Process\ProcessInterface>
     */
    public function getProcesses(bool $regenerateCache = false)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getProcesses($regenerateCache);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $logContext
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer|null $omsCheckConditionsQueryCriteriaTransfer
     *
     * @return int
     */
    public function checkConditions(array $logContext = [], ?OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer = null)
    {
        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->checkConditions($logContext, $omsCheckConditionsQueryCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $logContext
     * @param \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer|null $omsCheckTimeoutsQueryCriteriaTransfer
     *
     * @return int
     */
    public function checkTimeouts(array $logContext = [], ?OmsCheckTimeoutsQueryCriteriaTransfer $omsCheckTimeoutsQueryCriteriaTransfer = null)
    {
        $factory = $this->getFactory();
        $orderStateMachine = $factory
            ->createLockedOrderStateMachine($logContext);

        return $factory->createOrderStateMachineTimeout()
            ->checkTimeouts($orderStateMachine, $omsCheckTimeoutsQueryCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return array<\Spryker\Zed\Oms\Business\Process\Event>
     */
    public function getGroupedManuallyExecutableEvents(SpySalesOrder $order)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getGroupedManuallyExecutableEvents($order);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getItemsWithFlag($order, $flag);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem>
     */
    public function getItemsWithoutFlag(SpySalesOrder $order, $flag)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getItemsWithoutFlag($order, $flag);
    }

    /**
     * {@inheritDoc}
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
    public function getLogForOrder(SpySalesOrder $order, array $logContext = [])
    {
        return $this->getFactory()
            ->createUtilTransitionLog($logContext)
            ->getLogForOrder($order);
    }

    /**
     * {@inheritDoc}
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
    public function sumReservedProductQuantitiesForSku(string $sku, ?StoreTransfer $storeTransfer = null): Decimal
    {
        return $this->getFactory()
            ->createReservationReader()
            ->sumReservedProductQuantitiesForSku($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getOmsReservedProductQuantityForSku(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        return $this->getFactory()
            ->createReservationReader()
            ->getOmsReservedProductQuantityForSku($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $skus
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getOmsReservedProductQuantityForSkus(array $skus, StoreTransfer $storeTransfer): Decimal
    {
        return $this->getFactory()
            ->createReservationReader()
            ->getOmsReservedProductQuantityForSkus($skus, $storeTransfer);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        $orderItemsArray = $orderItems->getData();

        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array<string, mixed> $data
     *
     * @return array|null
     */
    public function triggerEventForNewItem(ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        $orderItemsArray = $orderItems->getData();

        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->triggerEventForNewItem($orderItemsArray, $data);
    }

    /**
     * {@inheritDoc}
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
    public function triggerEventForOneItem($eventId, $orderItem, array $logContext, array $data = [])
    {
        $orderItemsArray = [$orderItem];

        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Oms\Business\OmsFacadeInterface::getOrderMatrixCollection()} instead.
     *
     * @return array
     */
    public function getOrderItemMatrix()
    {
        return $this->getFactory()->createUtilOrderItemMatrix()->getMatrix();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderMatrixCollectionTransfer
     */
    public function getOrderMatrixCollection(OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer): OrderMatrixCollectionTransfer
    {
        return $this->getRepository()->getOrderMatrixCollection($orderMatrixCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<int, string>
     */
    public function getProcessNamesIndexedByIdOmsOrderProcess(): array
    {
        return $this->getRepository()->getProcessNamesIndexedByIdOmsOrderProcess();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array<array<string>>
     */
    public function getManualEventsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array<string>
     */
    public function getDistinctManualEventsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getDistinctManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array<string>
     */
    public function getGroupedDistinctManualEventsByIdSalesOrder(int $idSalesOrder): array
    {
        return $this->getFactory()
            ->createManualOrderReader()
            ->getGroupedDistinctManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function clearLocks()
    {
        $this->getFactory()->createTriggerLocker()->clearLocks();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderConfirmationMail(SpySalesOrder $salesOrderEntity)
    {
        $this->getFactory()->createMailHandler()->sendOrderConfirmationMail($salesOrderEntity);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderShippedMail(SpySalesOrder $salesOrderEntity)
    {
        $this->getFactory()->createMailHandler()->sendOrderShippedMail($salesOrderEntity);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return void
     */
    public function saveReservationVersion($sku, ?StoreTransfer $storeTransfer = null)
    {
        $this->getFactory()->createReservationVersionHandler()->saveReservationVersion($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function importReservation(
        OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
    ) {
        $this->getFactory()->createReservationWriter()->saveReservationRequest($omsAvailabilityReservationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function exportReservation()
    {
        $this->getFactory()->createExportReservation()->exportReservation();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getReservationsFromOtherStores(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        return $this->getFactory()->createReservationReader()->getReservationsFromOtherStores($sku, $storeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return int
     */
    public function getLastExportedReservationVersion()
    {
        return $this->getFactory()->createExportReservation()->getLastExportedVersion();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $processName
     * @param string $stateName
     *
     * @return array<string>
     */
    public function getStateFlags(string $processName, string $stateName): array
    {
        return $this->getFactory()->createOrderStateMachineFlagReader()->getStateFlags($processName, $stateName);
    }

    /**
     * {@inheritDoc}
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
    public function saveReservation(string $sku, StoreTransfer $storeTransfer, Decimal $reservationQuantity): void
    {
        $this->getFactory()
            ->createUtilReservation()
            ->saveReservation($sku, $storeTransfer, $reservationQuantity);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link updateReservation()} instead.
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity(string $sku): void
    {
        $this->getFactory()
            ->createUtilReservation()
            ->updateReservationQuantity($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function updateReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $this->getFactory()
            ->createUtilReservation()
            ->updateReservation($reservationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return \Generated\Shared\Transfer\OmsStateCollectionTransfer
     */
    public function getOmsReservedStateCollection(): OmsStateCollectionTransfer
    {
        return $this->getFactory()->createReservationReader()->getOmsReservedStateCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithStateHistory(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createStateHistoryExpander()
            ->expandOrderItemsWithStateHistory($itemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithOmsStates(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createOrderExpander()
            ->expandOrderWithOmsStates($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return array<array<string>>
     */
    public function getOrderItemManualEvents(OrderItemFilterTransfer $orderItemFilterTransfer): array
    {
        return $this->getFactory()
            ->createStateMachineReader()
            ->getOrderItemManualEvents($orderItemFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getOmsReservedProductQuantity(ReservationRequestTransfer $reservationRequestTransfer): ReservationResponseTransfer
    {
        return $this->getFactory()->createReservationReader()->getOmsReservedProductQuantity($reservationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithItemState(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createOrderItemStateExpander()
            ->expandOrderItemsWithItemState($itemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function expandOrdersWithAggregatedItemStates(array $orderTransfers): array
    {
        return $this->getFactory()
            ->createOrderAggregatedItemStateExpander()
            ->expandOrdersWithAggregatedItemStates($orderTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function setOrderIsCancellableByItemState(array $orderTransfers): array
    {
        return $this->getFactory()
            ->createOrderExpander()
            ->setOrderIsCancellableByItemState($orderTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function sendOrderStatusChangedMessage(int $idSalesOrder): void
    {
        $this->getFactory()->createOrderStatusChangedMessageSender()->sendMessage($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $flag
     *
     * @return bool
     */
    public function areOrderItemsSatisfiedByFlag(OrderTransfer $orderTransfer, string $flag): bool
    {
        return $this->getFactory()
            ->createOrderChecker()
            ->areOrderItemsSatisfiedByFlag($orderTransfer, $flag);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer $omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionResponseTransfer
     */
    public function deleteOmsOrderItemStateHistoryCollection(
        OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer $omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer
    ): OmsOrderItemStateHistoryCollectionResponseTransfer {
        return $this->getFactory()
            ->createOmsOrderItemStateHistoryDeleter()
            ->deleteOmsOrderItemStateHistoryCollection($omsOrderItemStateHistoryCollectionDeleteCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsTransitionLogCollectionDeleteCriteriaTransfer $omsTransitionLogCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsTransitionLogCollectionResponseTransfer
     */
    public function deleteOmsTransitionLogCollection(
        OmsTransitionLogCollectionDeleteCriteriaTransfer $omsTransitionLogCollectionDeleteCriteriaTransfer
    ): OmsTransitionLogCollectionResponseTransfer {
        return $this->getFactory()
            ->createOmsTransitionLogDeleter()
            ->deleteOmsTransitionLogCollection($omsTransitionLogCollectionDeleteCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsEventTimeoutCollectionDeleteCriteriaTransfer $omsEventTimeoutCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OmsEventTimeoutCollectionResponseTransfer
     */
    public function deleteOmsEventTimeoutCollection(
        OmsEventTimeoutCollectionDeleteCriteriaTransfer $omsEventTimeoutCollectionDeleteCriteriaTransfer
    ): OmsEventTimeoutCollectionResponseTransfer {
        return $this->getFactory()
            ->createOmsEventTimeoutDeleter()
            ->deleteOmsEventTimeoutCollection($omsEventTimeoutCollectionDeleteCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateTransfer
     */
    public function getOmsOrderItemState(string $stateName): OmsOrderItemStateTransfer
    {
        return $this->getFactory()
            ->createOrderStateMachinePersistenceManager()
            ->getOmsOrderItemState($stateName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|string $identifier
     * @param bool $blocking
     *
     * @return bool
     */
    public function acquireOrderItemLock(array|string $identifier, bool $blocking): bool
    {
        return $this->getFactory()
            ->createTriggerLocker()
            ->acquire($identifier, null, $blocking);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|string $identifier
     *
     * @return void
     */
    public function releaseOrderItemLock(array|string $identifier): void
    {
        $this->getFactory()
            ->createTriggerLocker()
            ->release($identifier);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|string $identifier
     * @param bool $blocking
     *
     * @return bool
     */
    public function acquireOrderLock(array|string $identifier, bool $blocking): bool
    {
        return $this->getFactory()
            ->createTriggerLocker()
            ->acquireForOrder($identifier, $blocking);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|string $identifier
     *
     * @return void
     */
    public function releaseOrderLock(array|string $identifier): void
    {
        $this->getFactory()
            ->createTriggerLocker()
            ->releaseForOrder($identifier);
    }
}
