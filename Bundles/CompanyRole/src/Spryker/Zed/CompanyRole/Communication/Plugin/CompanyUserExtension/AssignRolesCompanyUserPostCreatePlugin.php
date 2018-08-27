<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Communication\Plugin\CompanyUserExtension;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 */
class AssignRolesCompanyUserPostCreatePlugin extends AbstractPlugin implements CompanyUserPostCreatePluginInterface
{
    /**
     * Specification:
     * - Saves company user role collection if it's not empty.
     * - Uses CompanyRoleFacade to save company user.
     * - Is being called after new company user has been created and company roles were added to company user transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function postCreate(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
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
