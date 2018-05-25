<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyRoleBuilder;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyRoleHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $companyRole
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function haveCompanyRole(array $companyRole = []): CompanyRoleTransfer
    {
        $companyRoleTransfer = (new CompanyRoleBuilder($companyRole))->build();

        return $this->getCompanyRoleFacade()
            ->create($companyRoleTransfer)
            ->getCompanyRoleTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function assignCompanyRolesToCompanyUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $companyUserTransfer->requireCompanyRoleCollection();

        $this->getCompanyRoleFacade()->saveCompanyUser($companyUserTransfer);
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface
     */
    protected function getCompanyRoleFacade(): CompanyRoleFacadeInterface
    {
        return $this->getLocator()->companyRole()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface
     */
    protected function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getLocator()->companyUser()->facade();
    }
}
