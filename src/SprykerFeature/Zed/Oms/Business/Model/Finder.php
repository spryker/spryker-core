<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
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
     * @param string $sku
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
     * @param $sku
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
     * @param StatusInterface[] $states
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
            $statusName = $item->getStatus()->getName();
            $eventsByItem[$itemId] = array();

            if (!isset($eventsBySource[$statusName])) {
                continue;
            }
            $manualEvents = $eventsBySource[$statusName];
            $eventsByItem[$itemId] = $manualEvents;
        }

        $allEvents = array();
        foreach ($order->getItems() as $item) {
            $statusName = $item->getStatus()->getName();
            $events = $process->getStatusFromAllProcesses($statusName)->getEvents();
            foreach ($events as $event) {
                if ($event->isManual()) {
                    $allEvents[] = $event->getName();
                }
            }
        }

        $allEventsByItem = array();
        foreach ($order->getItems() as $item) {
            $statusName = $item->getStatus()->getName();
            if (isset($eventsBySource[$statusName])) {
                $events = $eventsBySource[$statusName];
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
     * @return SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag)
    {
        return $this->getItemsByFlag($order, $flag, true);
    }

    /**
     * @param SpySalesOrder $order
     * @param string $flag
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
        $statuses = $this->getStatusesByFlag($item->getProcess()->getName(), $flag, $hasFlag);

        $selectedItems = [];
        foreach ($items as $item) {
            if (array_key_exists($item->getStatus()->getName(), $statuses)) {
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
     * @return StatusInterface[]
     */
    protected function getStatusesByFlag($processName, $flag, $hasFlag)
    {
        $selectedStatuses = [];
        $builder = clone $this->builder;
        $processStatusList = $builder->createProcess($processName)->getAllStatuses();
        foreach ($processStatusList as $status) {
            if (($hasFlag && $status->hasFlag($flag)) || (!$hasFlag && !$status->hasFlag($flag))) {
                $selectedStatuses[$status->getName()] = $status;
            }
        }

        return $selectedStatuses;
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
            $reservedStates = array_merge($reservedStates, $process->getAllReservedStatuses());
        }

        return $reservedStates;
    }

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return string
     */
    public function getStatusDisplayName(SpySalesOrderItem $orderItem)
    {
        $processName = $orderItem->getProcess()->getName();
        $builder = clone $this->builder;
        $process = $builder->createProcess($processName);
        $statusName = $orderItem->getStatus()->getName();
        $status = $process->getStatus($statusName);

        return $status->getDisplay();
    }

}
