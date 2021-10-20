<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class SuggestController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_COMPANY = 'idCompany';

    /**
     * @var string
     */
    protected const PARAM_ID_COMPANY_USER = 'idCompanyUser';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        return $this->viewResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $companyUserTransfer = $this->createCompanyUserTransfer($request);
        $companyUserRoleAutoSuggestForm = $this->getFactory()
            ->createCompanyUserRoleAutoSuggestForm($companyUserTransfer);

        return [
            'form' => $companyUserRoleAutoSuggestForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(Request $request): CompanyUserTransfer
    {
        $idCompany = $this->castId($request->query->get(static::PARAM_ID_COMPANY));
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCompany($idCompany);

        if ($request->query->get(static::PARAM_ID_COMPANY_USER)) {
            $idCompanyUser = $this->castId($request->query->get(static::PARAM_ID_COMPANY_USER));
            $assignedCompanyRoleCollection = $this->getAssignedCompanyRoleCollection($idCompanyUser);

            $companyUserTransfer->setIdCompanyUser($idCompanyUser)
                ->setCompanyRoleCollection($assignedCompanyRoleCollection);
        }

        return $companyUserTransfer;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    protected function getAssignedCompanyRoleCollection(int $idCompanyUser): CompanyRoleCollectionTransfer
    {
        $companyRoleCriteriaFilterTransfer = (new CompanyRoleCriteriaFilterTransfer())
            ->setIdCompanyUser($idCompanyUser)
            ->setWithoutExpanders(true);

        $companyRoleCollection = $this->getFactory()
            ->getCompanyRoleFacade()
            ->getCompanyRoleCollection($companyRoleCriteriaFilterTransfer);

        return $companyRoleCollection;
    }
}
