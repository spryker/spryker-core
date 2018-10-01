<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class DeleteCompanyRoleController extends AbstractController
{
    protected const PARAMETER_ID_COMPANY_ROLE = 'id-company-role';

    protected const MESSAGE_DEFAULT_COMPANY_ROLE_DELETE_ERROR = 'You can not delete a default role, please set another default role before delete action';
    protected const MESSAGE_COMPANY_ROLE_DELETE_SUCCESS = 'Company role has been successfully removed';
    protected const MESSAGE_COMPANY_ROLE_DELETE_ERROR = 'Company role can not be removed';
    protected const MESSAGE_COMPANY_ROLE_WITHOUT_ID_ERROR = 'No company role ID provided';

    protected const PARAM_REFERER = 'referer';
    protected const REDIRECT_URL_DEFAULT = '/company-role-gui/list-company-role';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        $idCompanyRole = $request->query->getInt(static::PARAMETER_ID_COMPANY_ROLE);

        if (!$idCompanyRole) {
            throw new NotFoundHttpException(static::MESSAGE_COMPANY_ROLE_WITHOUT_ID_ERROR);
        }

        $companyRoleResponseTransfer = $this->getFactory()
            ->getCompanyRoleFacade()
            ->delete((new CompanyRoleTransfer())->setIdCompanyRole($idCompanyRole));

        if ($companyRoleResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_COMPANY_ROLE_DELETE_SUCCESS);
        } else {
            $this->addErrorMessage(static::MESSAGE_COMPANY_ROLE_DELETE_ERROR);
        }

        return $this->redirectResponse(static::REDIRECT_URL_DEFAULT);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idCompanyRole = $request->query->getInt(static::PARAMETER_ID_COMPANY_ROLE);

        if (!$idCompanyRole) {
            throw new NotFoundHttpException(static::MESSAGE_COMPANY_ROLE_WITHOUT_ID_ERROR);
        }

        $companyRoleTransfer = $this->getFactory()
            ->getCompanyRoleFacade()
            ->getCompanyRoleById((new CompanyRoleTransfer())->setIdCompanyRole($idCompanyRole));

        if ($companyRoleTransfer->getIsDefault()) {
            $this->addErrorMessage(static::MESSAGE_DEFAULT_COMPANY_ROLE_DELETE_ERROR);

            return $this->redirectToReferer($request);
        }

        return $this->viewResponse([
            'companyRoleTransfer' => $companyRoleTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        return $this->redirectResponse(
            $request->headers->get(static::PARAM_REFERER, static::REDIRECT_URL_DEFAULT)
        );
    }
}
