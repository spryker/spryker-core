<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipGui\Communication\MerchantRelationshipGuiCommunicationFactory getFactory()
 */
class ListMerchantRelationshipController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idCompany = $request->get('id-company', null);

        $merchantRelationshipTable = $this->getFactory()
            ->createMerchantRelationshipTable($idCompany);

        $companies = $this->getFactory()
            ->getCompanyFacade()
            ->getCompanies();

        return $this->viewResponse([
            'companies' => $companies->getCompanies(),
            'idCompany' => $idCompany,
            'merchantRelationships' => $merchantRelationshipTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $idCompany = $request->get('id-company', null);

        $merchantRelationshipTable = $this->getFactory()
            ->createMerchantRelationshipTable($idCompany);

        return $this->jsonResponse($merchantRelationshipTable->fetchData());
    }
}
