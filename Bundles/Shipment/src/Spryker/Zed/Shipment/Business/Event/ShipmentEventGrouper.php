<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Event;

use Spryker\Service\Shipment\ShipmentServiceInterface;

class ShipmentEventGrouper implements ShipmentEventGrouperInterface
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     */
    public function __construct(ShipmentServiceInterface $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param array $events
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return string[][]
     */
    public function groupEventsByShipment(array $events, iterable $orderItemTransfers): array
    {
        $events = $this->groupEventsByIdSalesShipment($events, $orderItemTransfers);

        return $this->retrieveEventNamesFromEventList($events);
    }

    /**
     * @param array $events
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return string[][]
     */
    protected function groupEventsByIdSalesShipment(array $events, iterable $orderItemTransfers): array
    {
        $groupedEvents = [];
        $shipmentGroupTransferCollection = $this->shipmentService->groupItemsByShipment($orderItemTransfers);
        foreach ($shipmentGroupTransferCollection as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->requireShipment();
            $idSalesShipment = $shipmentGroupTransfer->getShipment()->getIdSalesShipment();
            if ($idSalesShipment === null) {
                continue;
            }

            $idSalesShipment = (int)$idSalesShipment;
            foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                if (isset($events[$itemTransfer->getIdSalesOrderItem()]) === false) {
                    continue;
                }

                $groupedEvents[$idSalesShipment][] = $events[$itemTransfer->getIdSalesOrderItem()];
            }
        }

        return $groupedEvents;
    }

    /**
     * @param array $events
     *
     * @return string[][]
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
     * @return string[][]
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
}
