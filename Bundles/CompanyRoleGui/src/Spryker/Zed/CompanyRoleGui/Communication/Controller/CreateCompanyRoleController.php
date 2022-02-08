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
    /**
     * @var string
     */
    protected const URL_REDIRECT_LIST_COMPANY_ROLE = '/company-role-gui/list-company-role';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS_COMPANY_ROLE_CREATE = 'Company role has been successfully created';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_COMPANY_ROLE_CREATE = 'Company role cannot be created';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $companyRoleForm = $this->getFactory()
            ->createCompanyRoleCreateForm()
            ->handleRequest($request);

        if ($companyRoleForm->isSubmitted() && $companyRoleForm->isValid()) {
            /** @var \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer */
            $companyRoleTransfer = $companyRoleForm->getData();

            $companyRoleResponseTransfer = $this->getFactory()
                ->getCompanyRoleFacade()->create($companyRoleTransfer);

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
