<?php

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use SprykerFeature\Zed\Oms\Business\Process\StateInterface;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class Finder implements FinderInterface
{

    /**
     * @var OmsQueryContainer
     */
    protected $queryContainer;

    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @var array
     */
    protected $activeProcesses;

    /**
     * @param OmsQueryContainer $queryContainer
     * @param BuilderInterface $builder
     * @param array $activeProcesses
     */
    public function __construct(OmsQueryContainer $queryContainer, BuilderInterface $builder, array $activeProcesses)
    {
        $this->queryContainer = $queryContainer;
        $this->builder = $builder;
        $this->activeProcesses = $activeProcesses;
    }

    /**
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlagged($idOrder, $flag)
    {
        $order = $this->queryContainer
            ->getOrder($idOrder)
            ->findOne()
        ;

        $flaggedOrderItems = $this->getItemsByFlag($order, $flag, true);

        return (count($flaggedOrderItems) > 0);
    }

    /**
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlaggedAll($idOrder, $flag)
    {
        $order = $this->queryContainer
            ->getOrder($idOrder)
            ->findOne()
        ;

        $orderItems = $this->queryContainer
            ->getOrderItemsByOrder($idOrder)
            ->find()
        ;

        $flaggedOrderItems = $this->getItemsByFlag($order, $flag, true);

        foreach ($orderItems as $orderItem) {
            if (in_array($orderItem, $flaggedOrderItems) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $sku
     *
     * @return SpySalesOrderItemQuery
     */
    public function getReservedOrderItemsForSku($sku)
    {
        return $this->getOrderItemsForSku($this->retrieveReservedStates(), $sku, false);
    }

    /**
     * @param string $sku
     *
     * @return SpySalesOrderItem
     */
    public function countReservedOrderItemsForSku($sku)
    {
        return $this->countOrderItemsForSku($this->retrieveReservedStates(), $sku, false);
    }

    /**
     * @param array $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return SpySalesOrderItemQuery
     */
    protected function getOrderItemsForSku(array $states, $sku, $returnTest = true)
    {
        $orderItems = $this->queryContainer->getOrderItemsForSku($states, $sku, $returnTest);

        return $orderItems;
    }

    /**
     * @param StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return SpySalesOrderItem
     */
    protected function countOrderItemsForSku(array $states, $sku, $returnTest = true)
    {
        return $this->queryContainer->countOrderItemsForSku($states, $sku, $returnTest)->findOne();
    }

    /**
     * FIXME core-121 refactor method
     *
     * @param SpySalesOrder $order
     *
     * @return array
     */
    public function getGroupedManuallyExecutableEvents(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $processBuilder = clone $this->builder;
        $processName = current($order->getItems())->getProcess()->getName();
        $process = $processBuilder->createProcess($processName);
        $eventsBySource = $process->getManualEventsBySource();

        $eventsByItem = array();
        foreach ($order->getItems() as $item) {
            $itemId = $item->getIdSalesOrderItem();
            $stateName = $item->getState()->getName();
            $eventsByItem[$itemId] = array();

            if (!isset($eventsBySource[$stateName])) {
                continue;
            }
            $manualEvents = $eventsBySource[$stateName];
            $eventsByItem[$itemId] = $manualEvents;
        }

        $allEvents = array();
        foreach ($order->getItems() as $item) {
            $stateName = $item->getState()->getName();
            $events = $process->getStateFromAllProcesses($stateName)->getEvents();
            foreach ($events as $event) {
                if ($event->isManual()) {
                    $allEvents[] = $event->getName();
                }
            }
        }

        $allEventsByItem = array();
        foreach ($order->getItems() as $item) {
            $stateName = $item->getState()->getName();
            if (isset($eventsBySource[$stateName])) {
                $events = $eventsBySource[$stateName];
                $allEventsByItem[$item->getIdSalesOrderItem()] = $events;
            }
        }

        $uniqueItemEvents = array();
        $orderEvents = array_unique($allEvents);

        $result = [
            'order_events' => $orderEvents,
            'unique_item_events' => $uniqueItemEvents,
            'item_events' => $eventsByItem
        ];

        return $result;
    }

    /**
     * @param SpySalesOrder $order
     * @param string $flag
     *
     * @return SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag)
    {
        return $this->getItemsByFlag($order, $flag, true);
    }

    /**
     * @param SpySalesOrder $order
     * @param string $flag
     *
     * @return SpySalesOrderItem[]
     */
    public function getItemsWithoutFlag(SpySalesOrder $order, $flag)
    {
        return $this->getItemsByFlag($order, $flag, false);
    }

    /**
     * @return ProcessInterface[]
     */
    public function getProcesses()
    {
        $processes = [];
        foreach ($this->activeProcesses as $processName) {
            $builder = clone $this->builder;
            $processes[$processName] = $builder->createProcess($processName);
        }

        return $processes;
    }

    /**
     * @param SpySalesOrder $order
     * @param string $flag
     * @param bool $hasFlag
     *
     * @return SpySalesOrderItem[]
     */
    protected function getItemsByFlag(SpySalesOrder $order, $flag, $hasFlag)
    {
        $items = $order->getItems();
        $item = current($items);
        $states = $this->getStatesByFlag($item->getProcess()->getName(), $flag, $hasFlag);

        $selectedItems = [];
        foreach ($items as $item) {
            if (array_key_exists($item->getState()->getName(), $states)) {
                $selectedItems[] = $item;
            }
        }

        return $selectedItems;
    }

    /**
     * @param string $processName
     * @param string $flag
     * @param boolean $hasFlag
     *
     * @return StateInterface[]
     */
    protected function getStatesByFlag($processName, $flag, $hasFlag)
    {
        $selectedStates = [];
        $builder = clone $this->builder;
        $processStateList = $builder->createProcess($processName)->getAllStates();
        foreach ($processStateList as $state) {
            if (($hasFlag && $state->hasFlag($flag)) || (!$hasFlag && !$state->hasFlag($flag))) {
                $selectedStates[$state->getName()] = $state;
            }
        }

        return $selectedStates;
    }

    /**
     * @return array
     */
    protected function retrieveReservedStates()
    {
        $reservedStates = [];
        foreach ($this->activeProcesses as $processName) {
            $builder = clone $this->builder;
            $process = $builder->createProcess($processName);
            $reservedStates = array_merge($reservedStates, $process->getAllReservedStates());
        }

        return $reservedStates;
    }

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return string
     */
    public function getStateDisplayName(SpySalesOrderItem $orderItem)
    {
        $processName = $orderItem->getProcess()->getName();
        $builder = clone $this->builder;
        $process = $builder->createProcess($processName);
        $stateName = $orderItem->getState()->getName();
        $state = $process->getState($stateName);

        return $state->getDisplay();
    }

}
