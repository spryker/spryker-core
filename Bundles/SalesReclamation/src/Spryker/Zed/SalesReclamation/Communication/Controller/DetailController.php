<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Controller;

use Generated\Shared\Transfer\ReclamationTransfer;
use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationItemTableMap;
use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesReclamation\SalesReclamationConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReclamation\Business\SalesReclamationFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesReclamation\Communication\SalesReclamationCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface getQueryContainer()
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
        $idReclamation = $this->castId($request->get(SalesReclamationConfig::PARAM_ID_RECLAMATION));
        $reclamationTransfer = new ReclamationTransfer();
        $reclamationTransfer->setIdSalesReclamation($idReclamation);

        $reclamationTransfer = $this->getFacade()
            ->hydrateReclamationByIdReclamation($reclamationTransfer);

        if (!$reclamationTransfer) {
            $this->addErrorMessage(sprintf('No reclamation with given id %s', $idReclamation));

            return $this->redirectResponse('/sales-reclamation/');
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
        $idReclamation = $this->castId($request->get(SalesReclamationConfig::PARAM_ID_RECLAMATION));

        $reclamation = $this->getQueryContainer()
            ->queryReclamations()
            ->findOneByIdSalesReclamation($idReclamation);

        if (!$reclamation) {
            $this->addErrorMessage(sprintf('Reclamation with id %s not exists', $idReclamation));

            return $this->redirectResponse(
                Url::generate(
                    '/sales-reclamation'
                )->build()
            );
        }

        $reclamation->setState(SpySalesReclamationTableMap::COL_STATE_CLOSE);
        $reclamation->save();

        $this->addSuccessMessage(sprintf('Reclamation with id %s closed', $idReclamation));

        return $this->redirectResponse(
            Url::generate(
                '/sales-reclamation'
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
        $idReclamation = $this->castId($request->get(SalesReclamationConfig::PARAM_ID_RECLAMATION));
        $idReclamationItem = $this->castId($request->get(SalesReclamationConfig::PARAM_ID_RECLAMATION_ITEM));

        $reclamationItem = $this->getQueryContainer()
            ->queryReclamationItems()
            ->findOneByIdSalesReclamationItem($idReclamationItem);

        if (!$reclamationItem) {
            $this->addErrorMessage(sprintf('Reclamation item with id %s not exists', $idReclamationItem));

            return $this->redirectResponse(
                Url::generate(
                    '/sales-reclamation'
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
                    '/sales-reclamation'
                )->build()
            );
        }

        $reclamationItem->setState(SpySalesReclamationItemTableMap::COL_STATE_REFUNDED);
        $reclamationItem->save();

        $this->addSuccessMessage('Reclamation item refunded');

        return $this->redirectResponse(
            Url::generate(
                '/sales-reclamation/detail',
                [
                    SalesReclamationConfig::PARAM_ID_RECLAMATION => $idReclamation,
                ]
            )->build()
        );
    }
}
