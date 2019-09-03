<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Event;

use ArrayObject;

class ShipmentEventGrouper implements ShipmentEventGrouperInterface
{
    /**
     * @param array $events
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return array
     */
    public function groupEventsByShipment(array $events, ArrayObject $orderItemTransfers): array
    {
        $events = $this->groupEventsByIdSalesShipment($events, $orderItemTransfers);

        return $this->retrieveEventNamesFromEventList($events);
    }

    /**
     * @param array $events
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return array
     */
    protected function groupEventsByIdSalesShipment(array $events, ArrayObject $orderItemTransfers): array
    {
        $groupedEvents = [];

        foreach ($orderItemTransfers as $itemTransfer) {
            $itemTransfer->requireShipment();
            $idSalesShipment = $itemTransfer->getShipment()->getIdSalesShipment();

            if ($idSalesShipment === null || !isset($events[$idSalesShipment])) {
                continue;
            }

            $groupedEvents[(int)$idSalesShipment][] = $events[$idSalesShipment];
        }

        return $groupedEvents;
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
}
