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
class EditCompanyRoleController extends AbstractController
{
    protected const URL_REDIRECT_LIST_COMPANY_ROLE = '/company-role-gui/list-company-role';

    protected const MESSAGE_SUCCESS_COMPANY_ROLE_UPDATE = 'Company role has been successfully updated';
    protected const MESSAGE_ERROR_COMPANY_ROLE_UPDATE = 'Company role cannot be updated';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $companyRoleForm = $this->getFactory()
            ->createCompanyRoleEditForm()
            ->handleRequest($request);

        $viewData = [
            'companyRoleEditForm' => $companyRoleForm->createView(),
        ];

        if ($companyRoleForm->isSubmitted() && $companyRoleForm->isValid()) {
            $companyRoleFormData = $companyRoleForm->getData();

            $this->getFactory()
                ->getCompanyRoleFacade()
                ->update($companyRoleFormData);

            $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_ROLE_UPDATE);

            $this->redirectResponse(static::URL_REDIRECT_LIST_COMPANY_ROLE);
        }

        $this->addErrorMessage(static::MESSAGE_ERROR_COMPANY_ROLE_UPDATE);

        return $this->viewResponse($viewData);
    }
}
