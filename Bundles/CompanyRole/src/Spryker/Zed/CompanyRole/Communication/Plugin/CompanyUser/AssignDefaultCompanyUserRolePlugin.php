<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyRole\CompanyRoleConfig getConfig()
 */
class AssignDefaultCompanyUserRolePlugin extends AbstractPlugin implements CompanyUserPostCreatePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function postCreate(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        return $this->assignDefaultRoleToCompanyUser($companyUserResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function assignDefaultRoleToCompanyUser(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer
    {
        $companyUserTransfer = $companyUserResponseTransfer->getCompanyUser();

        $companyRoleCollectionTransfer = $companyUserTransfer->getCompanyRoleCollection();

        if ($companyRoleCollectionTransfer !== null) {
            $companyUserTransfer = $this->assignDefaultRole($companyUserTransfer);
        } else {
            $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();
            $companyUserTransfer->setCompanyRoleCollection($companyRoleCollectionTransfer);
            $companyUserTransfer = $this->assignDefaultRole($companyUserTransfer);
        }

        $this->getFacade()->saveCompanyUser($companyUserTransfer);
        $companyUserResponseTransfer->setCompanyUser($companyUserTransfer);

        return $companyUserResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function assignDefaultRole(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        $defaultCompanyRole = $this->getFacade()->getDefaultCompanyRole();
        $companyUserTransfer->getCompanyRoleCollection()->addRole($defaultCompanyRole);

        return $companyUserTransfer;
    }
}
