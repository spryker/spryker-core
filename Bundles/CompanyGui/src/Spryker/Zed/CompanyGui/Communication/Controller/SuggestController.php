<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyCriteriaFilterTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyGui\Communication\CompanyGuiCommunicationFactory getFactory()
 */
class SuggestController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_TERM = 'term';

    /**
     * @var string
     */
    protected const KEY_RESULTS = 'results';

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
        $suggestionName = (string)$request->query->get(static::PARAM_TERM);
        $companyCriteriaFilterTransfer = $this->createCompanyCriteriaFilterTransfer($suggestionName);

        $companyCollectionTransfer = $this->getFactory()
            ->getCompanyFacade()
            ->getCompanyCollection($companyCriteriaFilterTransfer);

        $formattedCompanyList = $this->getFactory()
            ->createCompanyGuiFormatter()
            ->formatCompanyCollectionToSuggestions($companyCollectionTransfer);

        return [
            static::KEY_RESULTS => $formattedCompanyList,
        ];
    }

    /**
     * @param string $suggestionName
     *
     * @return \Generated\Shared\Transfer\CompanyCriteriaFilterTransfer
     */
    protected function createCompanyCriteriaFilterTransfer(string $suggestionName): CompanyCriteriaFilterTransfer
    {
        $limit = $this->getFactory()->getConfig()->getCompanySuggestionLimit();

        $companyCriteriaFilterTransfer = new CompanyCriteriaFilterTransfer();
        $filterTransfer = (new FilterTransfer())
            ->setLimit($limit);

        return $companyCriteriaFilterTransfer
            ->setName($suggestionName)
            ->setFilter($filterTransfer)
            ->setWithoutExpanders(true);
    }
}
