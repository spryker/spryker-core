<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication\Controller;

use ArrayObject;
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
    protected const RECLAMATION_CLOSE_STATE = 'Close';

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
            ->expandReclamation($reclamationTransfer);

        if (!$reclamationTransfer->getIdSalesReclamation()) {
            $this->addErrorMessage(sprintf('No reclamation with given id %s', $idReclamation));

            return $this->redirectResponse('/sales-reclamation-gui/');
        }

        /** @var array[] $eventsGroupedByItem */
        $eventsGroupedByItem = $this->getFactory()
            ->getOmsFacade()
            ->getManualEventsByIdSalesOrder($reclamationTransfer->getOrder()->getIdSalesOrder());
        $events = $this->getEventsPerReclamationItems($reclamationTransfer->getReclamationItems(), $eventsGroupedByItem);

        return $this->viewResponse([
            'reclamation' => $reclamationTransfer,
            'eventsGroupedByItem' => $eventsGroupedByItem,
            'events' => $events,
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
            ->getReclamationById($reclamationTransfer);

        if (!$reclamationTransfer->getIdSalesReclamation()) {
            $this->addErrorMessage(sprintf('Reclamation with id %s not exists', $idReclamation));

            return $this->redirectResponse(
                Url::generate(
                    '/sales-reclamation-gui'
                )->build()
            );
        }

        $reclamationTransfer->setState(static::RECLAMATION_CLOSE_STATE);
        $this->getFactory()
            ->getSalesReclamationFacade()
            ->updateReclamation($reclamationTransfer);

        $this->addSuccessMessage(sprintf('Reclamation with id %s closed', $idReclamation));

        return $this->redirectResponse(
            Url::generate(
                '/sales-reclamation-gui'
            )->build()
        );
    }

    /**
     * @param \ArrayObject $reclamationItems
     * @param array $eventsGroupedByItem
     *
     * @return string[]
     */
    protected function getEventsPerReclamationItems(ArrayObject $reclamationItems, array $eventsGroupedByItem): array
    {
        $orderItemsIds = $this->getOrderItemsIdsByReclamationItems($reclamationItems);
        $events = [];
        foreach ($orderItemsIds as $orderItemId) {
            if (!isset($eventsGroupedByItem[$orderItemId])) {
                continue;
            }
            $events = array_merge($events, $eventsGroupedByItem[$orderItemId]);
        }

        return array_unique($events);
    }

    /**
     * @param \ArrayObject $reclamationItems
     *
     * @return int[]
     */
    protected function getOrderItemsIdsByReclamationItems(ArrayObject $reclamationItems): array
    {
        foreach ($reclamationItems as $item) {
            $orderItemsIds[] = $item->getOrderItem()->getIdSalesOrderItem();
        }

        return $orderItemsIds ?? [];
    }
}
