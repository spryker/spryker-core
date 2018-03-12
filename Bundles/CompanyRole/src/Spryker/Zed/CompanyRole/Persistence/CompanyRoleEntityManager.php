<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer;
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
        $entityTransfer = $this->getFactory()
            ->createCompanyRoleMapper()
            ->mapCompanyRoleTransferToEntityTransfer($companyRoleTransfer, new SpyCompanyRoleEntityTransfer());

        $this->cleanupDefaultRoles($entityTransfer);
        $entityTransfer = $this->save($entityTransfer);

        return $this->getFactory()
            ->createCompanyRoleMapper()
            ->mapEntityTransferToCompanyRoleTransfer($entityTransfer, $companyRoleTransfer);
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

        $spyCompanyRoleToPermission->setConfiguration(\json_encode($permissionTransfer->getConfiguration()));
        $spyCompanyRoleToPermission->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer
     *
     * @return void
     */
    protected function cleanupDefaultRoles(SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer): void
    {
        $isDefault = $companyRoleEntityTransfer->getIsDefault();

        if ($isDefault === true) {
            $query = $this->getFactory()->createCompanyRoleQuery();
            if ($companyRoleEntityTransfer->getIdCompanyRole() !== null) {
                $query->filterByIdCompanyRole($companyRoleEntityTransfer->getIdCompanyRole(), Criteria::NOT_EQUAL);
            }

            $query->update(['IsDefault' => false]);
        }
    }
}
