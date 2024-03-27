<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyRoleBuilder;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface;
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
     * @param array<string, mixed> $companyRoleSeed
     * @param list<\Generated\Shared\Transfer\PermissionTransfer> $permissionTransfers
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function haveCompanyRoleWithPermissions(array $companyRoleSeed, array $permissionTransfers = []): CompanyRoleTransfer
    {
        $companyRoleTransfer = (new CompanyRoleBuilder($companyRoleSeed))->build();

        if ($permissionTransfers) {
            $companyRoleTransfer->setPermissionCollection(
                (new PermissionCollectionTransfer())->setPermissions(new ArrayObject($permissionTransfers)),
            );
        }

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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param list<\Generated\Shared\Transfer\CompanyRoleTransfer> $companyRoleTransfers
     *
     * @return void
     */
    public function assignCompanyRoles(CompanyUserTransfer $companyUserTransfer, array $companyRoleTransfers): void
    {
        $companyUserTransfer->setCompanyRoleCollection(
            (new CompanyRoleCollectionTransfer())->setRoles(new ArrayObject($companyRoleTransfers)),
        );

        $this->assignCompanyRolesToCompanyUser($companyUserTransfer);
    }

    /**
     * @return \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface
     */
    protected function getCompanyRoleFacade(): CompanyRoleFacadeInterface
    {
        return $this->getLocator()->companyRole()->facade();
    }
}
