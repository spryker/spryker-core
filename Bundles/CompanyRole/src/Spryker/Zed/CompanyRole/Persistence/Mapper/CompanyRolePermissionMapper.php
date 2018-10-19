<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;

class CompanyRolePermissionMapper implements CompanyRolePermissionMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $spyCompanyRole
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function hydratePermissionCollection(
        SpyCompanyRole $spyCompanyRole,
        CompanyRoleTransfer $companyRoleTransfer
    ): CompanyRoleTransfer {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($spyCompanyRole->getSpyCompanyRoleToPermissionsJoinPermission() as $spyCompanyRoleToPermission) {
            $permissionTransfer = (new PermissionTransfer())
                ->setIdPermission($spyCompanyRoleToPermission->getFkPermission())
                ->setConfiguration(json_decode($spyCompanyRoleToPermission->getConfiguration(), true))
                ->setKey($spyCompanyRoleToPermission->getPermission()->getKey());

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        $companyRoleTransfer->setPermissionCollection($permissionCollectionTransfer);

        return $companyRoleTransfer;
    }
}
