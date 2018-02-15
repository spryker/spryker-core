<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Communication\Controller;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function createAction(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->getFacade()->create($companyRoleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollectionAction(CompanyRoleCollectionTransfer $companyRoleCollectionTransfer): CompanyRoleCollectionTransfer
    {
        return $this->getFacade()->getCompanyRoleCollection($companyRoleCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleByIdAction(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        return $this->getFacade()->getCompanyRoleById($companyRoleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function updateCompanyRoleAction(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        $this->getFacade()->update($companyRoleTransfer);

        $response = new CompanyRoleResponseTransfer();
        $response->setIsSuccessful(true);

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissionsAction(CompanyRoleTransfer $companyRoleTransfer): PermissionCollectionTransfer
    {
        $companyRoleTransfer->requireIdCompanyRole();

        return $this->getFacade()->findCompanyRolePermissions($companyRoleTransfer->getIdCompanyRole());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function deleteCompanyRoleAction(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        $this->getFacade()->delete($companyRoleTransfer);

        $response = new CompanyRoleResponseTransfer();
        $response->setIsSuccessful(true);

        return $response;
    }
}
