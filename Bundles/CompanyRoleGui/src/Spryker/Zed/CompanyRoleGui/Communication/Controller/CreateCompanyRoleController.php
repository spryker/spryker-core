<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class CreateCompanyRoleController extends AbstractController
{
    protected const URL_REDIRECT_LIST_COMPANY_ROLE = '/company-role-gui/list-company-role';

    protected const MESSAGE_SUCCESS_COMPANY_ROLE_CREATE = 'Company role has been successfully created';
    protected const MESSAGE_ERROR_COMPANY_ROLE_CREATE = 'Company role cannot be created';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $companyRoleForm = $this->getFactory()
            ->createCompanyRoleCreateForm()
            ->handleRequest($request);

        if ($companyRoleForm->isSubmitted() && $companyRoleForm->isValid()) {
            $companyRoleFormData = $companyRoleForm->getData();

            $companyRoleResponseTransfer = $this->getFactory()
                ->getCompanyRoleFacade()->create($companyRoleFormData);

            if ($companyRoleResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_ROLE_CREATE);

                return $this->redirectResponse(static::URL_REDIRECT_LIST_COMPANY_ROLE);
            }

            $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_ROLE_CREATE);
        }

        return $this->viewResponse([
            'form' => $companyRoleForm->createView(),
        ]);
    }
}
