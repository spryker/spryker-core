<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer;

class CompanyRolePermissionMapper implements CompanyRolePermissionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function hydratePermissionCollection(
        SpyCompanyRoleEntityTransfer $companyRoleEntityTransfer,
        CompanyRoleTransfer $companyRoleTransfer
    ): CompanyRoleTransfer {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($companyRoleEntityTransfer->getSpyCompanyRoleToPermissions() as $roleToPermission) {
            $permissionTransfer = (new PermissionTransfer())
                ->setIdPermission($roleToPermission->getFkPermission())
                ->setConfiguration(\json_decode($roleToPermission->getConfiguration(), true))
                ->setKey($roleToPermission->getPermission()->getKey());

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        $companyRoleTransfer->setPermissionCollection($permissionCollectionTransfer);

        return $companyRoleTransfer;
    }
}
