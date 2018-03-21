<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Controller;

use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\SalesReclamation\SalesReclamationConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
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

        return $this->viewResponse([
            'idReclamation' => $idReclamation,
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
}
