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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Oms\Business\OmsBusinessFactory getFactory()
 */
class OmsFacade extends AbstractFacade implements OmsFacadeInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $orderItemIds
     * @param array $data
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface[]
     */
    public function getProcesses()
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->getProcesses();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
        return $this->getFactory()
            ->createUtilTransitionLog($logContext)
            ->getLogForOrder($order);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku, ?StoreTransfer $storeTransfer = null)
    {
        return $this->getFactory()
            ->createUtilReservation()
            ->sumReservedProductQuantitiesForSku($sku, $storeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku, StoreTransfer $storeTransfer)
    {
        return $this->getFactory()
            ->createUtilReservation()
            ->getOmsReservedProductQuantityForSku($sku, $storeTransfer);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        $orderItemsArray = $orderItems->getData();

        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
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
     * {@inheritdoc}
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
    public function triggerEventForOneItem($eventId, $orderItem, array $logContext, array $data = [])
    {
        $orderItemsArray = [$orderItem];

        return $this->getFactory()
            ->createLockedOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function saveReservationVersion($sku)
    {
        $this->getFactory()->createReservationVersionHandler()->saveReservationVersion($sku);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getReservationsFromOtherStores($sku, StoreTransfer $storeTransfer)
    {
        return $this->getFactory()->createUtilReservation()->getReservationsFromOtherStores($sku, $storeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     */
    public function getLastExportedReservationVersion()
    {
        return $this->getFactory()->createExportReservation()->getLastExportedVersion();
    }
}
