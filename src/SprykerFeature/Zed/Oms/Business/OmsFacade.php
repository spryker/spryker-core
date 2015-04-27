<?php

namespace SprykerFeature\Zed\Oms\Business;

use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Zed\Oms\Business\Model\Dummy;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Oms\Business\Model\Process;
use SprykerFeature\Zed\Oms\Business\Model\Process\Event;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;
use SprykerFeature\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;

/**
 * @method OmsDependencyContainer getDependencyContainer()
 */
class OmsFacade extends AbstractFacade implements AvailabilityToOmsFacadeInterface
{
    /**
     * @param $eventId
     * @param ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = array())
    {
        assert(is_string($eventId));
        $orderItemsArray = $this->getDependencyContainer()
            ->createModelUtilCollectionToArrayTransformer()
            ->transformCollectionToArray($orderItems);

        return $this->getDependencyContainer()
            ->createModelOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     * @param ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForNewItem(ObjectCollection $orderItems, array $logContext, array $data = array())
    {
        $orderItemsArray = $this->getDependencyContainer()
            ->createModelUtilCollectionToArrayTransformer()
            ->transformCollectionToArray($orderItems);

        return $this->getDependencyContainer()
            ->createModelOrderStateMachine($logContext)
            ->triggerEventForNewItem($orderItemsArray, $data);
    }

    /**
     * @return Process[]
     */
    public function getProcesses()
    {
        return $this->getDependencyContainer()
            ->createModelFinder()
            ->getProcesses();
    }

    /**
     * @return array
     */
    public function getProcessList()
    {
        return $this->getDependencyContainer()
            ->createSettings()
            ->getActiveProcesses();
    }

    /**
     * @param string $eventId
     * @param Order $orderItem
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEventForOneItem($eventId, $orderItem, array $logContext, array $data = array())
    {
        $orderItemsArray = array($orderItem);

        return $this->getDependencyContainer()
            ->createModelOrderStateMachine($logContext)
            ->triggerEvent($eventId, $orderItemsArray, $data);
    }

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext)
    {
        return $this->getDependencyContainer()
            ->createModelOrderStateMachine($logContext)
            ->checkConditions();
    }

    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkTimeouts(array $logContext)
    {
        $orderStateMachine = $this->getDependencyContainer()
            ->createModelOrderStateMachine($logContext);

        return $this->getDependencyContainer()
            ->createModelOrderStateMachineTimeout($logContext)
            ->checkTimeouts($orderStateMachine);
    }

    /**
     * @param string $processName
     * @param bool $highlightState
     * @param null $format
     * @param null $fontsize
     *
     * @return bool
     */
    public function drawProcess($processName, $highlightState = null, $format = null, $fontsize = null)
    {
        $process = $this->getDependencyContainer()
            ->createModelBuilder()
            ->createProcess($processName);

        return $process->draw($highlightState, $format, $fontsize);
    }

    /**
     * @deprecated
     * @param string $processName
     *
     * @return Process
     */
    public function getProcess($processName)
    {
        return $this->getDependencyContainer()
            ->createModelBuilder()
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
            ->createModelFinder()
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
            ->createModelFinder()
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
            ->createModelFinder()
            ->getItemsWithoutFlag($order, $flag);
    }

    /**
     * @param SpySalesOrder $order
     *
     * @return PropelObjectCollection
     */
    public function getLogForOrder(SpySalesOrder $order)
    {
        // FIXME Ticket core-119
        return $this->getDependencyContainer()
            ->createModelUtilTransitionLog()
            ->getLogForOrder($order);
    }

    /**
     * @param string $sku
     *
     * @return \SprykerFeature_Zed_Library_Propel_ClearAllReferencesIterator
     */
    public function getReservedOrderItemsForSku($sku)
    {
        return $this->getDependencyContainer()
            ->createModelFinder()
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
            ->createModelFinder()
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
            ->createModelPersistenceManager()
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
            ->createModelPersistenceManager()
            ->getProcessEntity($processName);
    }

    /**
     * @return SpyOmsOrderItemState
     */
    public function getInitialStateEntity()
    {
        return $this->getDependencyContainer()
            ->createModelPersistenceManager()
            ->getInitialStateEntity();
    }

    /**
     * @param Order $transferOrder
     *
     * @return string
     */
    public function selectProcess(Order $transferOrder)
    {
        return $this->getDependencyContainer()
            ->createSettings()
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
            ->createModelFinder()
            ->getStateDisplayName($orderItem);
    }

}
