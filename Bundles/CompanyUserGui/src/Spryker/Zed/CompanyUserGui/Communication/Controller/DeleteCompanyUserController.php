<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class DeleteCompanyUserController extends AbstractController
{
    protected const PARAMETER_ID_COMPANY_USER = 'id-company-user';
    protected const PARAMETER_HEADER_REFERRER = 'referer';

    protected const MESSAGE_SUCCESS_COMPANY_USER_DELETE = 'company.account.company_user.delete.successful';
    protected const MESSAGE_ERROR_COMPANY_USER_DELETE = 'company.account.company_user.delete.error';

    protected const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idCompanyUser = $request->query->getInt(static::PARAMETER_ID_COMPANY_USER);
        if (!$idCompanyUser) {
            return $this->redirectToCompanyUserListWithErrorMessage();
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->delete($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $this->redirectToCompanyUserListWithErrorMessage();
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_DELETE);

        return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function confirmDeleteAction(Request $request)
    {
        $idCompanyUser = $request->query->getInt(static::PARAMETER_ID_COMPANY_USER);
        $refererHeader = (string)$request->headers->get(static::PARAMETER_HEADER_REFERRER);

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->getCompanyUserById($idCompanyUser);

        $companyUserTransfer
            ->requireIdCompanyUser()
            ->requireCustomer()
            ->requireCompany();

        return $this->viewResponse([
            'companyUser' => $companyUserTransfer,
            'referer' => $refererHeader,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToCompanyUserListWithErrorMessage(): RedirectResponse
    {
        $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_DELETE);

        return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
    }
}
