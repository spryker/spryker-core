<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Controller;

use Spryker\Shared\ShipmentGui\ShipmentGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ShipmentGui\Communication\Exception\OrderNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\ShipmentGui\Communication\Exception\OrderNotFoundException
     *
     * @return array
     */
    public function itemsAction(Request $request)
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer|null $orderTransfer */
        $orderTransfer = $request->attributes->get('orderTransfer');

        if ($orderTransfer === null) {
            throw new OrderNotFoundException();
        }

        $shipmentGroupsCollection = $this->getFactory()
            ->getShipmentService()
            ->groupItemsByShipment($orderTransfer->getItems());

        return $this->viewResponse([
            'events' => $request->attributes->get('events', []),
            'eventsGroupedByShipment' => $request->attributes->get('eventsGroupedByShipment', []),
            'eventsGroupedByItem' => $request->attributes->get('eventsGroupedByItem', []),
            'order' => $orderTransfer,
            'groupedOrderItemsByShipment' => $shipmentGroupsCollection,
            'changeStatusRedirectUrl' => $request->attributes->get('changeStatusRedirectUrl'),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\OrderNotFoundException
     *
     * @return array
     */
    public function shipmentExpensesAction(Request $request)
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer|null $orderTransfer */
        $orderTransfer = $request->attributes->get('orderTransfer');

        if ($orderTransfer === null) {
            throw new OrderNotFoundException();
        }

        return $this->viewResponse([
            'order' => $orderTransfer,
            'shipmentExpenseType' => ShipmentGuiConfig::SHIPMENT_EXPENSE_TYPE,
        ]);
    }
}
