<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiCommunicationFactory getFactory()
 */
class DeleteCompanyUserController extends AbstractController
{
    protected const MESSAGE_SUCCESS_COMPANY_USER_DELETE = 'Company user successfully removed.';
    protected const MESSAGE_ERROR_COMPANY_USER_DELETE = 'Company user cannot be removed.';
    protected const MESSAGE_COMPANY_USER_NOT_FOUND = 'Company user not found.';

    protected const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';

    protected const PARAM_ID_COMPANY_USER = 'id-company-user';

    /**
     * @deprecated Use DeleteCompanyUserController::deleteConfirmAction() instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function confirmDeleteAction(Request $request): array
    {
        $idCompanyUser = $this->castId($request->query->getInt(static::PARAM_ID_COMPANY_USER));

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->getCompanyUserById($idCompanyUser);

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
        $idCompanyUser = $this->castId($request->query->getInt(static::PARAM_ID_COMPANY_USER));

        $companyUserFacade = $this->getFactory()
            ->getCompanyUserFacade();

        $companyUserTransfer = $companyUserFacade->findCompanyUserById($idCompanyUser);

        if (!$companyUserTransfer) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_USER_NOT_FOUND);

            return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $companyUserResponseTransfer = $companyUserFacade
            ->deleteCompanyUser($companyUserTransfer);

        if ($companyUserResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_USER_DELETE);

            return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_USER_DELETE);

        return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteConfirmAction(Request $request)
    {
        $idCompanyUser = $this->castId($request->query->getInt(static::PARAM_ID_COMPANY_USER));

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserFacade()
            ->findCompanyUserById($idCompanyUser);

        if (!$companyUserTransfer) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_USER_NOT_FOUND);

            return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
        }

        return $this->viewResponse([
            'companyUser' => $companyUserTransfer,
        ]);
    }
}
