<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Business\Exception\OrderNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\OrderNotFoundException
     *
     * @return array
     */
    public function itemsAction(Request $request)
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $request->attributes->get('orderTransfer');
        if ($orderTransfer === null) {
            throw new OrderNotFoundException();
        }

        /** @var string[] $events */
        $events = $request->attributes->get('events', []);

        /** @var string[][] $events */
        $eventsGroupedByItem = $request->attributes->get('eventsGroupedByItem', []);

        $shipmentGroupsCollection = $this->getFactory()
            ->getShipmentService()
            ->groupItemsByShipment($orderTransfer->getItems());

        return $this->viewResponse([
            'events' => $events,
            'eventsGroupedByItem' => $eventsGroupedByItem,
            'order' => $orderTransfer,
            'groupedOrderItemsByShipment' => $shipmentGroupsCollection,
        ]);
    }
}
