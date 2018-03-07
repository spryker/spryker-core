<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Communication\Plugin;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyRole\CompanyRoleConfig getConfig()
 */
class CompanyRoleCreatePlugin extends AbstractPlugin implements CompanyPostCreatePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function postCreate(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        $this->createCompanyRole($companyTransfer);

        return $companyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    protected function createCompanyRole(CompanyTransfer $companyTransfer): void
    {
        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->setFkCompany($companyTransfer->getIdCompany())
            ->setName($this->getConfig()->getDefaultAdminRoleName())
            ->setIsDefault(true);

        $this->getFacade()->create($companyRoleTransfer);
    }
}
