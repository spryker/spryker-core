<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use SprykerFeature\Zed\Oms\Business\Process\ProcessInterface;
use SprykerFeature\Zed\Oms\Business\Process\StateInterface;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class Finder implements FinderInterface
{

    /**
     * @var OmsQueryContainerInterface
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
     * @param OmsQueryContainerInterface $queryContainer
     * @param BuilderInterface $builder
     * @param array $activeProcesses
     */
    public function __construct(OmsQueryContainerInterface $queryContainer, BuilderInterface $builder, array $activeProcesses)
    {
        $this->queryContainer = $queryContainer;
        $this->builder = $builder;
        $this->activeProcesses = $activeProcesses;
    }

    /**
     * @param int $idOrderItem
     *
     * @return string[]
     */
    public function getManualEvents($idOrderItem)
    {
        $orderItem = $this->queryContainer
            ->querySalesOrderItems([$idOrderItem])
            ->findOne()
        ;

        $state = $orderItem->getState()->getName();
        $processName = $orderItem->getProcess()->getName();

        $processBuilder = clone $this->builder;
        $events = $processBuilder->createProcess($processName)->getManualEventsBySource();

        if (!isset($events[$state])) {
            return [];
        }

        return $events[$state];
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
            ->querySalesOrderById($idOrder)
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
            ->querySalesOrderById($idOrder)
            ->findOne()
        ;

        $orderItems = $this->queryContainer
            ->querySalesOrderItemsByIdOrder($idOrder)
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
        $orderItems = $this->queryContainer->querySalesOrderItemsForSku($states, $sku, $returnTest);

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
        return $this->queryContainer->countSalesOrderItemsForSku($states, $sku, $returnTest)->findOne();
    }

    /**
     * FIXME core-121 refactor method
     *
     * @param SpySalesOrder $order
     *
     * @return array
     */
    public function getGroupedManuallyExecutableEvents(SpySalesOrder $order)
    {
        $processBuilder = clone $this->builder;
        $processName = $order->getItems()->getFirst()->getProcess()->getName();
        $process = $processBuilder->createProcess($processName);
        $eventsBySource = $process->getManualEventsBySource();

        $eventsByItem = [];
        foreach ($order->getItems() as $item) {
            $itemId = $item->getIdSalesOrderItem();
            $stateName = $item->getState()->getName();
            $eventsByItem[$itemId] = [];

            if (!isset($eventsBySource[$stateName])) {
                continue;
            }
            $manualEvents = $eventsBySource[$stateName];
            $eventsByItem[$itemId] = $manualEvents;
        }

        $allEvents = [];
        foreach ($order->getItems() as $item) {
            $stateName = $item->getState()->getName();
            $events = $process->getStateFromAllProcesses($stateName)->getEvents();
            foreach ($events as $event) {
                if ($event->isManual()) {
                    $allEvents[] = $event->getName();
                }
            }
        }

        $allEventsByItem = [];
        foreach ($order->getItems() as $item) {
            $stateName = $item->getState()->getName();
            if (isset($eventsBySource[$stateName])) {
                $events = $eventsBySource[$stateName];
                $allEventsByItem[$item->getIdSalesOrderItem()] = $events;
            }
        }

        $uniqueItemEvents = [];
        $orderEvents = array_unique($allEvents);

        $result = [
            'order_events' => $orderEvents,
            'unique_item_events' => $uniqueItemEvents,
            'item_events' => $eventsByItem,
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
        $states = $this->getStatesByFlag(
            $items->getFirst()->getProcess()->getName(),
            $flag,
            $hasFlag
        );

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
     * @param bool $hasFlag
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
