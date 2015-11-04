<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Oms\Business\OrderStateMachine\Dummy;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Oms\Business\Process\Process;
use SprykerFeature\Zed\Oms\Business\Process\Event;
use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLog;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use SprykerFeature\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;

/**
 * @method OmsDependencyContainer getDependencyContainer()
 */
class OmsFacade extends AbstractFacade implements AvailabilityToOmsFacadeInterface
{

    /**
     * @param int $idOrderItem
     *
     * @return string[]
     */
    public function getManualEvents($idOrderItem)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->getManualEvents($idOrderItem)
        ;
    }

    /**
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlagged($idOrder, $flag)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->isOrderFlagged($idOrder, $flag)
        ;
    }

    /**
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlaggedAll($idOrder, $flag)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->isOrderFlaggedAll($idOrder, $flag)
        ;
    }

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, array $data = [])
    {
        assert(is_string($eventId));

        return $this->getDependencyContainer()
            ->createOrderStateMachineOrderStateMachine()
            ->triggerEventForOrderItems($eventId, $orderItemIds, $data)
        ;
    }

    /**
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data = [])
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineOrderStateMachine()
            ->triggerEventForNewOrderItems($orderItemIds, $data)
        ;
    }

    /**
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, array $data = [])
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineOrderStateMachine()
            ->triggerEventForOneOrderItem($eventId, $orderItemId, $data)
        ;
    }

    /**
     * @return Process[]
     */
    public function getProcesses()
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->getProcesses();
    }

    /**
     * @return array
     */
    public function getProcessList()
    {
        return $this->getDependencyContainer()
            ->getConfig()
            ->getActiveProcesses();
    }

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext = [])
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineOrderStateMachine($logContext)
            ->checkConditions();
    }

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkTimeouts(array $logContext = [])
    {
        $orderStateMachine = $this->getDependencyContainer()
            ->createOrderStateMachineOrderStateMachine($logContext);

        return $this->getDependencyContainer()
            ->createOrderStateMachineTimeout()
            ->checkTimeouts($orderStateMachine);
    }

    /**
     * @param string $processName
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return bool
     */
    public function drawProcess($processName, $highlightState = null, $format = null, $fontSize = null)
    {
        $process = $this->getDependencyContainer()
            ->createOrderStateMachineBuilder()
            ->createProcess($processName);

        return $process->draw($highlightState, $format, $fontSize);
    }

    /**
     * @deprecated
     *
     * @param string $processName
     *
     * @return Process
     */
    public function getProcess($processName)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineBuilder()
            ->createProcess($processName);
    }

    /**
     * @deprecated
     *
     * @return Dummy
     */
    public function getDummy()
    {
        return $this->getDependencyContainer()
            ->createModelDummy();
    }

    /**
     * @param SpySalesOrder $order
     *
     * @return Event[]
     */
    public function getGroupedManuallyExecutableEvents(SpySalesOrder $order)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->getGroupedManuallyExecutableEvents($order);
    }

    /**
     * @param SpySalesOrder $order
     * @param string $flag
     *
     * @return SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->getItemsWithFlag($order, $flag);
    }

    /**
     * @param SpySalesOrder $order
     * @param string $flag
     *
     * @return SpySalesOrderItem[]
     */
    public function getItemsWithoutFlag(SpySalesOrder $order, $flag)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->getItemsWithoutFlag($order, $flag);
    }

    /**
     * @param SpySalesOrder $order
     * @param array $logContext
     *
     * @return SpyOmsTransitionLog[]
     */
    public function getLogForOrder(SpySalesOrder $order, array $logContext = [])
    {
        // FIXME Ticket core-119
        return $this->getDependencyContainer()
            ->createUtilTransitionLog($logContext)
            ->getLogForOrder($order);
    }

    /**
     * @param string $sku
     *
     * @return SpySalesOrderItem
     */
    public function getReservedOrderItemsForSku($sku)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->getReservedOrderItemsForSku($sku);
    }

    /**
     * @param string $sku
     *
     * @return SpySalesOrderItem
     */
    public function countReservedOrderItemsForSku($sku)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->countReservedOrderItemsForSku($sku);
    }

    /**
     * @param string $stateName
     *
     * @return SpyOmsOrderItemState
     */
    public function getStateEntity($stateName)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachinePersistenceManager()
            ->getStateEntity($stateName);
    }

    /**
     * @param string $processName
     *
     * @return SpyOmsOrderProcess
     */
    public function getProcessEntity($processName)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachinePersistenceManager()
            ->getProcessEntity($processName);
    }

    /**
     * @return SpyOmsOrderItemState
     */
    public function getInitialStateEntity()
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachinePersistenceManager()
            ->getInitialStateEntity();
    }

    /**
     * @param OrderTransfer $transferOrder
     *
     * @return string
     */
    public function selectProcess(OrderTransfer $transferOrder)
    {
        return $this->getDependencyContainer()
            ->getConfig()
            ->selectProcess($transferOrder);
    }

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return string
     */
    public function getStateDisplayName(SpySalesOrderItem $orderItem)
    {
        return $this->getDependencyContainer()
            ->createOrderStateMachineFinder()
            ->getStateDisplayName($orderItem);
    }

    /**
     * @param string $eventId
     * @param ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        assert(is_string($eventId));
        $orderItemsArray = $orderItems->getData();

        return $this->getDependencyContainer()
            ->createOrderStateMachineOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     * @param ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewItem(ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        $orderItemsArray = $orderItems->getData();

        return $this->getDependencyContainer()
            ->createOrderStateMachineOrderStateMachine($logContext)
            ->triggerEventForNewItem($orderItemsArray, $data);
    }

    /**
     * @param string $eventId
     * @param OrderTransfer $orderItem
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOneItem($eventId, $orderItem, array $logContext, array $data = [])
    {
        $orderItemsArray = [$orderItem];

        return $this->getDependencyContainer()
            ->createOrderStateMachineOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

}
