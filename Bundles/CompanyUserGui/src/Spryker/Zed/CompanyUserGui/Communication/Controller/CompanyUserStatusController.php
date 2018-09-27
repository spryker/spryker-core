<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserGui\Communication\Table\CompanyUserTableConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CompanyUserStatusController extends AbstractController
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
    public function enableCompanyUserAction(Request $request): RedirectResponse
    {
        $idCompanyUser = $request->query->get(CompanyUserTableConstants::PARAM_ID_COMPANY_USER);
        if (!$idCompanyUser) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_ENABLE);

            return $this->redirectResponse(CompanyUserTableConstants::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->enableCompanyUser($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_ENABLE);

            return $this->redirectResponse(CompanyUserTableConstants::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_ENABLE);

        return $this->redirectResponse(CompanyUserTableConstants::URL_REDIRECT_COMPANY_USER_PAGE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableCompanyUserAction(Request $request): RedirectResponse
    {
        $idCompanyUser = $request->query->get(CompanyUserTableConstants::PARAM_ID_COMPANY_USER);
        if (!$idCompanyUser) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_DISABLE);

            return $this->redirectResponse(CompanyUserTableConstants::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $companyUserResponseTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->disableCompanyUser($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_DISABLE);

            return $this->redirectResponse(CompanyUserTableConstants::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_DISABLE);

        return $this->redirectResponse(CompanyUserTableConstants::URL_REDIRECT_COMPANY_USER_PAGE);
    }
}
