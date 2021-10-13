<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class EditCompanyRoleController extends AbstractController
{
    /**
     * @var string
     */
    protected const URL_REDIRECT_LIST_COMPANY_ROLE = '/company-role-gui/list-company-role';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS_COMPANY_ROLE_UPDATE = 'Company role has been successfully updated';

    /**
     * @var string
     */
    protected const MESSAGE_COMPANY_ROLE_NOT_FOUND = 'Company role not found';

    /**
     * @var string
     */
    protected const REQUEST_ID_COMPANY_ROLE = 'id-company-role';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCompanyRole = $request->query->getInt(static::REQUEST_ID_COMPANY_ROLE);
        $companyRoleTransfer = (new CompanyRoleTransfer())
            ->setIdCompanyRole($idCompanyRole);

        $companyRoleForm = $this->getFactory()
            ->createCompanyRoleEditForm($companyRoleTransfer)
            ->handleRequest($request);

        if (!$companyRoleForm->getData()->getIdCompanyRole()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_ROLE_NOT_FOUND);

            return $this->redirectResponse(static::URL_REDIRECT_LIST_COMPANY_ROLE);
        }

        if ($companyRoleForm->isSubmitted() && $companyRoleForm->isValid()) {
            $companyRoleFormData = $companyRoleForm->getData();

            $this->getFactory()
                ->getCompanyRoleFacade()
                ->update($companyRoleFormData);

            $this->addSuccessMessage(static::MESSAGE_SUCCESS_COMPANY_ROLE_UPDATE);
        }

        return $this->viewResponse([
            'form' => $companyRoleForm->createView(),
        ]);
    }
}
