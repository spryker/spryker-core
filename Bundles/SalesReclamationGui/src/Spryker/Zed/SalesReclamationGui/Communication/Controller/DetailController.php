<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReclamationGui\Communication\SalesReclamationGuiCommunicationFactory getFactory()
 */
class DetailController extends AbstractController
{
    protected const PARAM_ID_RECLAMATION_ITEM = 'id-reclamation-item';
    protected const PARAM_ID_RECLAMATION = 'id-reclamation';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idReclamation = $this->castId($request->get(static::PARAM_ID_RECLAMATION));
        $reclamationTransfer = new ReclamationTransfer();
        $reclamationTransfer->setIdSalesReclamation($idReclamation);

        $reclamationTransfer = $this->getFactory()
            ->getSalesReclamationFacade()
            ->getReclamationById($reclamationTransfer);

        $eventsGroupedByItem = $this->getFactory()
            ->getOmsFacade()
            ->getManualEventsByIdSalesOrder($reclamationTransfer->getOrder()->getIdSalesOrder());

        $events = $this->getFactory()
            ->createReclamationItemEventsFinder()
            ->getDistinctManualEventsByReclamationItems($reclamationTransfer->getReclamationItems(), $eventsGroupedByItem);

        $orderOmsTriggerFormCollection = $this->getOrderOmsTriggerFormCollection($reclamationTransfer, $events);
        $orderItemsOmsTriggerFormCollection = $this->getOrderItemsOmsTriggerFormCollection($reclamationTransfer, $eventsGroupedByItem);

        return $this->viewResponse([
            'reclamation' => $reclamationTransfer,
            'eventsGroupedByItem' => $eventsGroupedByItem,
            'events' => $events,
            'orderOmsTriggerFormCollection' => $orderOmsTriggerFormCollection,
            'orderItemsOmsTriggerFormCollection' => $orderItemsOmsTriggerFormCollection,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function closeAction(Request $request): RedirectResponse
    {
        $idReclamation = $this->castId($request->get(static::PARAM_ID_RECLAMATION));

        $reclamationTransfer = new ReclamationTransfer();
        $reclamationTransfer->setIdSalesReclamation($idReclamation);

        $reclamationTransfer = $this->getFactory()
            ->getSalesReclamationFacade()
            ->closeReclamation($reclamationTransfer);

        $this->addSuccessMessage(
            sprintf('Reclamation with id %s closed', $reclamationTransfer->getIdSalesReclamation())
        );

        return $this->redirectResponse(
            Url::generate(
                '/sales-reclamation-gui'
            )->build()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param string[] $events
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    protected function getOrderOmsTriggerFormCollection(ReclamationTransfer $reclamationTransfer, array $events): array
    {
        $orderOmsTriggerFormCollection = [];

        foreach ($events as $event) {
            $orderOmsTriggerFormCollection[$event] = $this->getFactory()
                ->getOrderOmsTriggerForm($reclamationTransfer, $event)
                ->createView();
        }

        return $orderOmsTriggerFormCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param array $eventsGroupedByItem
     *
     * @return array
     */
    protected function getOrderItemsOmsTriggerFormCollection(ReclamationTransfer $reclamationTransfer, array $eventsGroupedByItem): array
    {
        $orderItemsOmsTriggerFormCollection = [];

        foreach ($reclamationTransfer->getOrder()->getItems() as $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();

            $orderItemsOmsTriggerFormCollection[$idSalesOrderItem] = $this->getSingleOrderItemOmsTriggerFormCollection(
                $itemTransfer,
                $eventsGroupedByItem[$idSalesOrderItem],
                $reclamationTransfer->getIdSalesReclamation()
            );
        }

        return $orderItemsOmsTriggerFormCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string[] $events
     * @param int $idReclamation
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    protected function getSingleOrderItemOmsTriggerFormCollection(ItemTransfer $itemTransfer, array $events, int $idReclamation): array
    {
        $orderItemOmsTriggerFormCollection = [];

        foreach ($events as $event) {
            $orderItemOmsTriggerFormCollection[$event] = $this->getFactory()
                ->getOrderItemOmsTriggerForm($itemTransfer, $event, $idReclamation)
                ->createView();
        }

        return $orderItemOmsTriggerFormCollection;
    }
}
