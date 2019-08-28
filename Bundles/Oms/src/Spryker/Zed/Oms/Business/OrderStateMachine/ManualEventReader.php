<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class ManualEventReader implements ManualEventReaderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        BuilderInterface $builder
    ) {
        $this->queryContainer = $queryContainer;
        $this->builder = $builder;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctManualEventsByIdSalesOrderGroupedByShipment(int $idSalesOrder): array
    {
        $events = $this->getManualEventsByIdSalesOrderGroupedByShipment($idSalesOrder);

        return $this->retrieveEventNamesFromEventList($events);
    }

    /**
     * @param array $events
     *
     * @return string[]
     */
    protected function retrieveEventNamesFromEventList(array $events): array
    {
        $eventList = [];

        foreach ($events as $shipmentId => $eventNameCollection) {
            $eventList = $this->expandEventListEventNameCollectionForShipment($eventList, $eventNameCollection, $shipmentId);
        }

        return $eventList;
    }

    /**
     * @param array $eventList
     * @param array $eventNameCollection
     * @param int $shipmentId
     *
     * @return array
     */
    protected function expandEventListEventNameCollectionForShipment(
        array $eventList,
        array $eventNameCollection,
        int $shipmentId
    ): array {
        foreach ($eventNameCollection as $eventNames) {
            $eventList[$shipmentId] = array_merge($eventList[$shipmentId] ?? [], $eventNames);
        }

        $eventList[$shipmentId] = array_unique($eventList[$shipmentId]);

        return $eventList;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    protected function getManualEventsByIdSalesOrderGroupedByShipment(int $idSalesOrder): array
    {
        $orderItems = $this->queryContainer->querySalesOrderItemsByIdSalesOrder($idSalesOrder)->find();

        return $this->groupEventsByShipment($orderItems);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return string[]
     */
    protected function groupEventsByShipment(ObjectCollection $orderItems): array
    {
        $events = [];

        foreach ($orderItems as $orderItemEntity) {
            $events[(int)$orderItemEntity->getFkSalesShipment()][] = $this->getManualEventsByOrderItemEntity($orderItemEntity);
        }

        return $events;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return string[]
     */
    protected function getManualEventsByOrderItemEntity(SpySalesOrderItem $orderItem): array
    {
        $state = $orderItem->getState()->getName();
        $processName = $orderItem->getProcess()->getName();

        $processBuilder = clone $this->builder;
        $events = $processBuilder->createProcess($processName)->getManualEventsBySource();

        if (!isset($events[$state])) {
            return [];
        }

        return $events[$state];
    }
}
