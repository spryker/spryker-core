<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\CompanyRole\Persistence\Base\SpyCompanyRoleToPermissionQuery;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;

class CompanyRoleRepository implements CompanyRoleRepositoryInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer
    {
        $companyRoleToPermissionEntities = SpyCompanyRoleToPermissionQuery::create()
            ->joinPermission()
            ->joinCompanyRole()
            ->useCompanyRoleQuery()
            ->joinSpyCompanyRoleToCompanyUser()
            ->useSpyCompanyRoleToCompanyUserQuery()
            ->filterByIdCompanyRoleToCompanyUser($idCompanyUser)
            ->endUse()
            ->endUse()
            ->find();

        //mapper
        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        foreach ($companyRoleToPermissionEntities as $companyRoleToPermissionEntity) {
            $permissionTransfer = new PermissionTransfer();
            $permissionTransfer->setConfiguration($companyRoleToPermissionEntity->getConfiguration());
            $permissionTransfer->setKey($companyRoleToPermissionEntity->getPermission()->getKey());

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function findCompanyRole(): CompanyRoleCollectionTransfer
    {
        $companyRoleEntities = SpyCompanyRoleQuery::create()
            ->joinSpyCompanyRoleToPermission()
            ->useSpyCompanyRoleToPermissionQuery()
                ->joinPermission()
            ->endUse()
            ->find();

        $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();

        foreach ($companyRoleEntities as $companyRoleEntity) {
            $companyRoleTransfer = (new CompanyRolePersistenceFactory)
                ->createCompanyRoleMapper()
                ->mapCompanyRoleEntityToTransfer(
                    $companyRoleEntity,
                    new CompanyRoleTransfer()
                );

            $companyRoleTransfer = (new CompanyRolePersistenceFactory())
                ->createCompanyRolePermissionMapper()
                ->hydratePermissionCollection(
                    $companyRoleEntity,
                    $companyRoleTransfer
                );

            $companyRoleCollectionTransfer->addRole($companyRoleTransfer);
        }

        return $companyRoleCollectionTransfer;
    }
}
