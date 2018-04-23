<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyRoleBuilder;
use Generated\Shared\DataBuilder\CompanyRoleCollectionBuilder;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyRoleHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $companyRole
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function haveCompanyRole(array $companyRole = []): CompanyRoleTransfer
    {
        $companyRoleTransfer = (new CompanyRoleBuilder($companyRole))->build();

        $companyRoleTransfer = $this->getCompanyRoleFacade()
            ->create($companyRoleTransfer)
            ->getCompanyRoleTransfer();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyRoleTransfer) {
            $this->getCompanyRoleFacade()->delete($companyRoleTransfer);
        });

        return $companyRoleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function assignCompanyRolesToCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        $companyUserTransfer->requireCompanyRoleCollection();

        $this->getCompanyRoleFacade()->saveCompanyUser($companyUserTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyUserTransfer) {
            $companyUserTransfer->setCompanyRoleCollection((new CompanyRoleCollectionBuilder())->build());
            $this->getCompanyRoleFacade()->saveCompanyUser($companyUserTransfer);
        });

        return $companyUserTransfer;
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
