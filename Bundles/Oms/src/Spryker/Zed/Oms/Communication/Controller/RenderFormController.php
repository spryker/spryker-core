<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface getRepository()
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 */
class RenderFormController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function orderAction(Request $request): array
    {
        $orderTransfer = $request->attributes->get('order');
        $events = $request->attributes->get('events');
        $orderOmsTriggerFormCollection = $this->getFactory()
            ->createOmsTriggerFormCollector()
            ->buildOrderOmsTriggerFormCollection($orderTransfer, $events);

        return $this->viewResponse([
            'formCollection' => $orderOmsTriggerFormCollection,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function orderItemAction(Request $request): array
    {
        $itemTransfer = $request->attributes->get('item');
        $eventsGroupedByItem = $request->attributes->get('eventsGroupedByItem');
        $orderItemOmsTriggerFormCollection = $this->getFactory()
            ->createOmsTriggerFormCollector()
            ->buildOrderItemOmsTriggerFormCollection($itemTransfer, $eventsGroupedByItem);

        return $this->viewResponse([
            'formCollection' => $orderItemOmsTriggerFormCollection,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function reclamationAction(Request $request): array
    {
        $reclamationTransfer = $request->attributes->get('reclamation');
        $events = $request->attributes->get('events');
        $reclamationOmsTriggerFormCollection = $this->getFactory()
            ->createOmsTriggerFormCollector()
            ->buildReclamationOmsTriggerFormCollection($reclamationTransfer, $events);

        return $this->viewResponse([
            'formCollection' => $reclamationOmsTriggerFormCollection,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function reclamationItemAction(Request $request): array
    {
        $reclamationTransfer = $request->attributes->get('reclamation');
        $eventsGroupedByItem = $request->attributes->get('eventsGroupedByItem');
        $itemTransfer = $request->attributes->get('orderItem');

        $reclamationItemsOmsTriggerFormCollection = $this->getFactory()
            ->createOmsTriggerFormCollector()
            ->buildReclamationItemOmsTriggerFormCollection($itemTransfer, $eventsGroupedByItem, $reclamationTransfer);

        return $this->viewResponse([
            'formCollection' => $reclamationItemsOmsTriggerFormCollection,
        ]);
    }
}
