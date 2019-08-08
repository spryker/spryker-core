<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication\Controller;

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
    protected const ROUTE_REDIRECT = '/sales-reclamation-gui/detail';

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

        $reclamationTransfer->requireOrder();

        $eventsGroupedByItem = $this->getFactory()
            ->getOmsFacade()
            ->getManualEventsByIdSalesOrder($reclamationTransfer->getOrder()->getIdSalesOrder());

        $events = $this->getFactory()
            ->createReclamationItemEventsFinder()
            ->getDistinctManualEventsByReclamationItems($reclamationTransfer->getReclamationItems(), $eventsGroupedByItem);

        $changeStatusRedirectUrl = $this->createRedirectLink($idReclamation);

        return $this->viewResponse([
            'reclamation' => $reclamationTransfer,
            'eventsGroupedByItem' => $eventsGroupedByItem,
            'events' => $events,
            'changeStatusRedirectUrl' => $changeStatusRedirectUrl,
            'idSalesOrder' => $reclamationTransfer->getOrder()->getIdSalesOrder(),
        ]);
    }

    /**
     * @param int $idReclamation
     *
     * @return string
     */
    protected function createRedirectLink(int $idReclamation): string
    {
        $redirectUrlParams = [
            static::PARAM_ID_RECLAMATION => $idReclamation,
        ];

        return Url::generate(static::ROUTE_REDIRECT, $redirectUrlParams);
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
}
