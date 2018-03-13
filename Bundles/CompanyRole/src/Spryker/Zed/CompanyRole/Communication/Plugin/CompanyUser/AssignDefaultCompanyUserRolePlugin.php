<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function postCreate(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $this->assignDefaultRoleToCompanyUser($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function assignDefaultRoleToCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        $defaultCompanyRole = $this->getFacade()->getDefaultCompanyRole();
        $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();
        $companyUserTransfer->setCompanyRoleCollection($companyRoleCollectionTransfer);
        $companyUserTransfer->getCompanyRoleCollection()->addRole($defaultCompanyRole);
        $this->getFacade()->saveCompanyUser($companyUserTransfer);

        return $companyUserTransfer;
    }
}
