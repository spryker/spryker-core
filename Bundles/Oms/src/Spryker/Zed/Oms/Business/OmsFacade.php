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
     * @api
     *
     * @param string $processName
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return bool
     */
    public function drawProcess($processName, $highlightState = null, $format = null, $fontSize = null)
    {
        $process = $this->getFactory()
            ->createOrderStateMachineBuilder()
            ->createProcess($processName);

        return $process->draw($highlightState, $format, $fontSize);
    }

    /**
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
     * @api
     *
     * @deprecated Use sumReservedProductQuantitiesForSku() instead
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function countReservedOrderItemsForSku($sku)
    {
        return $this->getFactory()
            ->createOrderStateMachineFinder()
            ->countReservedOrderItemsForSku($sku);
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
     * @api
     *
     * @return array
     */
    public function getOrderItemMatrix()
    {
        return $this->getFactory()->createUtilOrderItemMatrix()->getMatrix();
    }

    /**
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
     * @api
     *
     * @return void
     */
    public function clearLocks()
    {
        $this->getFactory()->createTriggerLocker()->clearLocks();
    }

}
