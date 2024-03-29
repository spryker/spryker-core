<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyRole\Business\CompanyRoleBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyRole\Persistence\CompanyRoleEntityManagerInterface getEntityManager()
 */
class CompanyRoleFacade extends AbstractFacade implements CompanyRoleFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        return $this->getRepository()
            ->getCompanyRoleById($companyRoleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function create(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyRole()
            ->create($companyRoleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function createByCompany(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyRole()
            ->createByCompany($companyResponseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function update(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $this->getFactory()
            ->createCompanyRole()
            ->update($companyRoleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function delete(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyRole()
            ->delete($companyRoleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function findCompanyRoles(): CompanyRoleCollectionTransfer
    {
        return $this->getRepository()
            ->findCompanyRole();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCompanyRole
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(int $idCompanyRole): PermissionCollectionTransfer
    {
        return $this->getRepository()
            ->findCompanyRolePermissions($idCompanyRole);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $this->getFactory()
            ->createCompanyRole()
            ->saveCompanyUser($companyUserTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function hydrateCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $this->getFactory()
            ->createCompanyRole()
            ->hydrateCompanyUser($companyUserTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer
    {
        return $this->getRepository()
            ->findPermissionsByIdCompanyUser($idCompanyUser);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCompanyRole
     * @param int $idPermission
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function findPermissionByIdCompanyRoleByIdPermission(int $idCompanyRole, int $idPermission): PermissionTransfer
    {
        return $this->getRepository()
            ->findPermissionsByIdCompanyRoleByIdPermission($idCompanyRole, $idPermission);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $permissionKey
     * @param int|null $idCompany
     *
     * @return array<int>
     */
    public function getCompanyUserIdsByPermissionKey(string $permissionKey, ?int $idCompany = null): array
    {
        return $this->getRepository()->getCompanyUserIdsByPermissionKey($permissionKey, $idCompany);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyRoleCollectionTransfer {
        return $this->getRepository()
            ->getCompanyRoleCollection($criteriaFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function updateCompanyRolePermission(PermissionTransfer $permissionTransfer): void
    {
        $this->getEntityManager()->updateCompanyRolePermission($permissionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface::findDefaultCompanyRoleByIdCompany()} instead.
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getDefaultCompanyRole(): CompanyRoleTransfer
    {
        return $this->getRepository()->getDefaultCompanyRole();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer|null
     */
    public function findDefaultCompanyRoleByIdCompany(int $idCompany): ?CompanyRoleTransfer
    {
        return $this->getRepository()
            ->findDefaultCompanyRoleByIdCompany($idCompany);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer|null
     */
    public function findCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): ?CompanyRoleTransfer
    {
        return $this->getRepository()
            ->findCompanyRoleById($companyRoleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * {@internal will work if UUID field is provided.}
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function findCompanyRoleByUuid(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->getFactory()->createCompanyRoleReader()->findCompanyRoleByUuid($companyRoleTransfer);
    }
}
