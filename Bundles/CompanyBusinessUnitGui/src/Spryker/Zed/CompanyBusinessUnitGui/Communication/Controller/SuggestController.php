<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 */
class SuggestController extends AbstractController
{
    /**
     * @var string
     */
    protected const KEY_RESULTS = 'results';

    /**
     * @var string
     */
    protected const PARAM_SUGGESTION = 'suggestion';

    /**
     * @var string
     */
    protected const PARAM_ID_COMPANY = 'idCompany';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $response = $this->executeIndexAction($request);

        return $this->jsonResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $companyBusinessUnitCriteriaFilterTransfer = $this->createCompanyBusinessUnitCriteriaFilterTransfer($request);

        $companyBusinessUnitCollectionTransfer = $this->getFactory()
            ->getCompanyBusinessUnitFacade()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        $formattedCompanyBusinessUnitList = $this->getFactory()
            ->createCompanyBusinessUnitGuiFormatter()
            ->formatCompanyBusinessUnitCollectionToSuggestions($companyBusinessUnitCollectionTransfer);

        return [
            static::KEY_RESULTS => $formattedCompanyBusinessUnitList,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer
     */
    protected function createCompanyBusinessUnitCriteriaFilterTransfer(Request $request): CompanyBusinessUnitCriteriaFilterTransfer
    {
        $suggestionName = $request->query->get(static::PARAM_SUGGESTION);
        $idCompany = $this->castId($request->query->get(static::PARAM_ID_COMPANY));

        $limit = $this->getFactory()->getConfig()->getCompanyBusinessUnitSuggestionLimit();

        $companyBusinessUnitCriteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
        $filterTransfer = (new FilterTransfer())
            ->setLimit($limit);

        return $companyBusinessUnitCriteriaFilterTransfer
            ->setName($suggestionName)
            ->setIdCompany($idCompany)
            ->setFilter($filterTransfer)
            ->setWithoutExpanders(true);
    }
}
