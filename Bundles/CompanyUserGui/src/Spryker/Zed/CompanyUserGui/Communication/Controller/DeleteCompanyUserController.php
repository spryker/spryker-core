<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class DeleteCompanyUserController extends AbstractController
{
    protected const MESSAGE_SUCCESS_COMPANY_USER_DELETE = 'Company user successfully removed';
    protected const MESSAGE_ERROR_COMPANY_USER_DELETE = 'Company user cannot be removed';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function confirmDeleteAction(Request $request): array
    {
        $idCompanyUser = $request->query->getInt(CompanyUserGuiConfig::PARAM_ID_COMPANY_USER);

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->getCompanyUserById($idCompanyUser);

        $companyUserTransfer
            ->requireIdCompanyUser()
            ->requireCustomer()
            ->requireCompany();

        return $this->viewResponse([
            'companyUser' => $companyUserTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        $idCompanyUser = $request->query->getInt(CompanyUserGuiConfig::PARAM_ID_COMPANY_USER);
        if (!$idCompanyUser) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_DELETE);

            return $this->redirectResponse(CompanyUserGuiConfig::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->delete($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_DELETE);

            return $this->redirectResponse(CompanyUserGuiConfig::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_DELETE);

        return $this->redirectResponse(CompanyUserGuiConfig::URL_REDIRECT_COMPANY_USER_PAGE);
    }
}
