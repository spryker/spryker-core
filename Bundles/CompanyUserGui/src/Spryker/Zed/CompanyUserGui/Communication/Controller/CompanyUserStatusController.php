<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CompanyUserStatusController extends AbstractCompanyUserController
{
    protected const MESSAGE_SUCCESS_COMPANY_USER_ENABLE = 'Company user successfully enabled';
    protected const MESSAGE_ERROR_COMPANY_USER_ENABLE = 'Company user cannot be enabled';

    protected const MESSAGE_SUCCESS_COMPANY_USER_DISABLE = 'Company user successfully disabled';
    protected const MESSAGE_ERROR_COMPANY_USER_DISABLE = 'Company user cannot be disabled';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableCompanyUserAction(Request $request)
    {
        $idCompanyUser = $request->query->get(static::PARAM_ID_COMPANY_USER);
        if (!$idCompanyUser) {
            return $this->redirectToCompanyUserListWithErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_ENABLE);
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->enableCompanyUser($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $this->redirectToCompanyUserListWithErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_ENABLE);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_ENABLE);

        return $this->redirectResponse(CompanyUserGuiConfig::URL_REDIRECT_COMPANY_USER_PAGE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableCompanyUserAction(Request $request)
    {
        $idCompanyUser = $request->query->get(static::PARAM_ID_COMPANY_USER);
        if (!$idCompanyUser) {
            return $this->redirectToCompanyUserListWithErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_DISABLE);
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->disableCompanyUser($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return $this->redirectToCompanyUserListWithErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_DISABLE);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_DISABLE);

        return $this->redirectResponse(CompanyUserGuiConfig::URL_REDIRECT_COMPANY_USER_PAGE);
    }
}
