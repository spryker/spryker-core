<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication\Controller;

use Generated\Shared\Transfer\ReclamationTransfer;
use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationItemTableMap;
use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesReclamationGui\SalesReclamationGuiConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReclamationGui\Communication\SalesReclamationGuiCommunicationFactory getFactory()
 */
class DetailController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idReclamation = $this->castId($request->get(SalesReclamationGuiConfig::PARAM_ID_RECLAMATION));
        $reclamationTransfer = new ReclamationTransfer();
        $reclamationTransfer->setIdSalesReclamation($idReclamation);

        $reclamationTransfer = $this->getFactory()
            ->getSalesReclamationFacade()
            ->hydrateReclamationByIdReclamation($reclamationTransfer);

        if (!$reclamationTransfer) {
            $this->addErrorMessage(sprintf('No reclamation with given id %s', $idReclamation));

            return $this->redirectResponse('/sales-reclamation-gui/');
        }

        return $this->viewResponse([
            'reclamation' => $reclamationTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function closeAction(Request $request): RedirectResponse
    {
        $idReclamation = $this->castId($request->get(SalesReclamationGuiConfig::PARAM_ID_RECLAMATION));

        $reclamation = $this->getFactory()
            ->getSalesReclamationQueryContainer()
            ->queryReclamations()
            ->findOneByIdSalesReclamation($idReclamation);

        if (!$reclamation->getIdSalesReclamation()) {
            $this->addErrorMessage(sprintf('Reclamation with id %s not exists', $idReclamation));

            return $this->redirectResponse(
                Url::generate(
                    '/sales-reclamation-gui'
                )->build()
            );
        }

        $reclamation->setState(SpySalesReclamationTableMap::COL_STATE_CLOSE);
        $reclamation->save();

        $this->addSuccessMessage(sprintf('Reclamation with id %s closed', $idReclamation));

        return $this->redirectResponse(
            Url::generate(
                '/sales-reclamation-gui'
            )->build()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function closeItemAction(Request $request): RedirectResponse
    {
        $idReclamation = $this->castId($request->get(SalesReclamationGuiConfig::PARAM_ID_RECLAMATION));
        $idReclamationItem = $this->castId($request->get(SalesReclamationGuiConfig::PARAM_ID_RECLAMATION_ITEM));

        $reclamationItem = $this->getFactory()
            ->getSalesReclamationQueryContainer()
            ->queryReclamationItems()
            ->findOneByIdSalesReclamationItem($idReclamationItem);

        if (!$reclamationItem->getIdSalesReclamationItem()) {
            $this->addErrorMessage(sprintf('Reclamation item with id %s not exists', $idReclamationItem));

            return $this->redirectResponse(
                Url::generate(
                    '/sales-reclamation-gui'
                )->build()
            );
        }
        if ($reclamationItem->getFkSalesReclamation() !== $idReclamation) {
            $this->addErrorMessage(sprintf(
                'Reclamation with id %s not own this item %s',
                $idReclamation,
                $idReclamationItem
            ));

            return $this->redirectResponse(
                Url::generate(
                    '/sales-reclamation-gui'
                )->build()
            );
        }

        $reclamationItem->setState(SpySalesReclamationItemTableMap::COL_STATE_REFUNDED);
        $reclamationItem->save();

        $this->addSuccessMessage('Reclamation item refunded');

        return $this->redirectResponse(
            Url::generate(
                '/sales-reclamation-gui/detail',
                [
                    SalesReclamationGuiConfig::PARAM_ID_RECLAMATION => $idReclamation,
                ]
            )->build()
        );
    }
}
