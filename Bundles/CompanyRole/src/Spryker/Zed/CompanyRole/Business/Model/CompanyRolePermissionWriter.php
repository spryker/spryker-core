<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business\Model;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface;

class CompanyRolePermissionWriter implements CompanyRolePermissionWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionReaderInterface
     */
    protected $permissionReader;

    /**
     * @var \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\CompanyRole\Business\Model\CompanyRolePermissionReaderInterface $permissionReader
     * @param \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface $entityManager
     */
    public function __construct(
        CompanyRolePermissionReaderInterface $permissionReader,
        CompanyRoleEntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->permissionReader = $permissionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function saveCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $companyRoleTransfer->requireIdCompanyRole();
        $currentPermissions = $this->getCompanyRolePermissions($companyRoleTransfer);
        $requestedPermissions = $this->getRequestedPermissions($companyRoleTransfer);

        $savePermissions = array_diff_key($requestedPermissions, $currentPermissions);
        $deletePermissions = array_diff_key($currentPermissions, $requestedPermissions);

        $this->addPermissions(
            $savePermissions,
            $companyRoleTransfer->getIdCompanyRole()
        );

        $this->removePermissions(
            array_keys($deletePermissions),
            $companyRoleTransfer->getIdCompanyRole()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return array
     */
    protected function getCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): array
    {
        $permissions = [];
        $permissionCollection = $this->permissionReader->getCompanyRolePermissions($companyRoleTransfer);
        foreach ($permissionCollection->getPermissions() as $permission) {
            $permissions[$permission->getIdPermission()] = $permission;
        }

        return $permissions;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return array
     */
    protected function getRequestedPermissions(CompanyRoleTransfer $companyRoleTransfer): array
    {
        $permissions = [];
        if ($companyRoleTransfer->getPermissionCollection() === null) {
            return $permissions;
        }

        foreach ($companyRoleTransfer->getPermissionCollection()->getPermissions() as $permission) {
            $permissions[$permission->getIdPermission()] = $permission;
        }

        return $permissions;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer[] $permissions
     * @param int $idCompanyRole
     *
     * @return void
     */
    protected function addPermissions(array $permissions, int $idCompanyRole): void
    {
        $this->entityManager->addPermissions($permissions, $idCompanyRole);
    }

    /**
     * @param array $idPermissions
     * @param int $idCompanyRole
     *
     * @return void
     */
    protected function removePermissions(array $idPermissions, int $idCompanyRole): void
    {
        $this->entityManager->removePermissions($idPermissions, $idCompanyRole);
    }
}
