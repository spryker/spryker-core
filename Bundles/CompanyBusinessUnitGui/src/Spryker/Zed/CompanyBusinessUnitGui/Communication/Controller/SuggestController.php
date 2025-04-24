<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Kernel\Exception\Controller\InvalidIdException;
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
    protected const PARAM_TERM = 'term';

    /**
     * @var string
     */
    protected const PARAM_ID_COMPANY = 'idCompany';

    /**
     * @var string
     */
    protected const PARAM_COMPANY_IDS = 'idsCompany';

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
     * @throws \Spryker\Zed\Kernel\Exception\Controller\InvalidIdException
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer
     */
    protected function createCompanyBusinessUnitCriteriaFilterTransfer(Request $request): CompanyBusinessUnitCriteriaFilterTransfer
    {
        $suggestionName = (string)$request->query->get(static::PARAM_SUGGESTION) ?: null;
        $suggestionNameAlternative = (string)$request->query->get(static::PARAM_TERM) ?: null;

        $limit = $this->getFactory()->getConfig()->getCompanyBusinessUnitSuggestionLimit();

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setName($suggestionName ?? $suggestionNameAlternative)
            ->setFilter((new FilterTransfer())->setLimit($limit))
            ->setWithoutExpanders(true);

        $idCompany = $request->query->has(static::PARAM_ID_COMPANY) ? $this->castId($request->query->get(static::PARAM_ID_COMPANY)) : null;

        if ($idCompany) {
            return $companyBusinessUnitCriteriaFilterTransfer->setIdCompany($idCompany);
        }

        if ($request->query->has(static::PARAM_COMPANY_IDS)) {
            foreach (explode(',', (string)$request->query->get(static::PARAM_COMPANY_IDS)) as $idCompany) {
                $companyBusinessUnitCriteriaFilterTransfer->addCompanyId($this->castId($idCompany));
            }
        }

        if (!$companyBusinessUnitCriteriaFilterTransfer->getIdCompany() && !$companyBusinessUnitCriteriaFilterTransfer->getCompanyIds()) {
            throw new InvalidIdException('idCompany or idsCompany parameter is required');
        }

        return $companyBusinessUnitCriteriaFilterTransfer;
    }
}
