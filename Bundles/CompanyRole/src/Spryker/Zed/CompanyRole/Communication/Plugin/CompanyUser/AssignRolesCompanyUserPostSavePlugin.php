<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 */
class AssignRolesCompanyUserPostSavePlugin extends AbstractPlugin implements CompanyUserPostSavePluginInterface
{
    /**
     * {@inheritdoc}
     * - Saves company user it has at least one assigned company role.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function postSave(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUser = $companyUserResponseTransfer->getCompanyUser();

        if ($companyUser->getCompanyRoleCollection() !== null &&
            $companyUser->getCompanyRoleCollection()->getRoles()->count()
        ) {
            $this->getFacade()->saveCompanyUser($companyUser);
        }

        return $companyUserResponseTransfer;
    }
}
