<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRolePersistenceFactory getFactory()
 */
class CompanyRoleEntityManager extends AbstractEntityManager implements CompanyRoleEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function saveCompanyRole(
        CompanyRoleTransfer $companyRoleTransfer
    ): CompanyRoleTransfer {
        $spyCompanyRole = $this->getFactory()
            ->createCompanyRoleMapper()
            ->mapCompanyRoleTransferToEntity($companyRoleTransfer, new SpyCompanyRole());

        if ($spyCompanyRole->getIsDefault()) {
            $this->cleanupCompanyDefaultRoles($spyCompanyRole);
        }

        $spyCompanyRole->save();

        return $this->getFactory()
            ->createCompanyRoleMapper()
            ->mapEntityToCompanyRoleTransfer($spyCompanyRole, $companyRoleTransfer);
    }

    /**
     * @param int $idCompanyRole
     *
     * @return void
     */
    public function deleteCompanyRoleById(int $idCompanyRole): void
    {
        $this->getFactory()
            ->createCompanyRoleQuery()
            ->filterByIdCompanyRole($idCompanyRole)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $companyRoles = [];

        if ($companyUserTransfer->getCompanyRoleCollection()) {
            $companyRoles = $companyUserTransfer->getCompanyRoleCollection()->getRoles();
        }

        $assignedIdCompanyRoles = [];

        foreach ($companyRoles as $companyRoleTransfer) {
            $this->getFactory()
                ->createCompanyRoleToCompanyUserQuery()
                ->filterByFkCompanyUser($companyUserTransfer->getIdCompanyUser())
                ->filterByFkCompanyRole($companyRoleTransfer->getIdCompanyRole())
                ->findOneOrCreate()
                ->save();

            $assignedIdCompanyRoles[] = $companyRoleTransfer->getIdCompanyRole();
        }

        $this->getFactory()
            ->createCompanyRoleToCompanyUserQuery()
            ->filterByFkCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->filterByFkCompanyRole($assignedIdCompanyRoles, Criteria::NOT_IN)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer[] $permissions
     * @param int $idCompanyRole
     *
     * @return void
     */
    public function addPermissions(array $permissions, int $idCompanyRole): void
    {
        foreach ($permissions as $permission) {
            $this->saveCompanyRolePermission($idCompanyRole, $permission);
        }
    }

    /**
     * @param array $idPermissions
     * @param int $idCompanyRole
     *
     * @return void
     */
    public function removePermissions(array $idPermissions, int $idCompanyRole): void
    {
        if (count($idPermissions) === 0) {
            return;
        }

        $this->getFactory()
            ->createCompanyRoleToPermissionQuery()
            ->filterByFkCompanyRole($idCompanyRole)
            ->filterByFkPermission_In($idPermissions)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function updateCompanyRolePermission(PermissionTransfer $permissionTransfer): void
    {
        $spyCompanyRoleToPermission = $this->getFactory()
            ->createCompanyRoleToPermissionQuery()
            ->filterByFkCompanyRole($permissionTransfer->getIdCompanyRole())
            ->filterByFkPermission($permissionTransfer->getIdPermission())
            ->findOne();

        if ($spyCompanyRoleToPermission !== null) {
            $spyCompanyRoleToPermission->setConfiguration(json_encode($permissionTransfer->getConfiguration()));
            $spyCompanyRoleToPermission->save();
        }
    }

    /**
     * @param int $idCompanyRole
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    protected function saveCompanyRolePermission(int $idCompanyRole, PermissionTransfer $permissionTransfer): void
    {
        $spyCompanyRoleToPermission = $this->getFactory()
            ->createCompanyRoleToPermissionQuery()
            ->filterByFkCompanyRole($idCompanyRole)
            ->filterByFkPermission($permissionTransfer->getIdPermission())
            ->findOneOrCreate();

        $spyCompanyRoleToPermission->setConfiguration(json_encode($permissionTransfer->getConfiguration()));
        $spyCompanyRoleToPermission->save();
    }

    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $spyCompanyRole
     *
     * @return void
     */
    protected function cleanupCompanyDefaultRoles(SpyCompanyRole $spyCompanyRole): void
    {
        $updateQuery = $this->getFactory()
            ->createCompanyRoleQuery()
            ->filterByFkCompany($spyCompanyRole->getFkCompany());

        if ($spyCompanyRole->getIdCompanyRole() !== null) {
            $updateQuery->filterByIdCompanyRole($spyCompanyRole->getIdCompanyRole(), Criteria::NOT_EQUAL);
        }

        $updateQuery->update(['IsDefault' => false]);
    }
}
