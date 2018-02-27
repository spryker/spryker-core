<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRole;

class CompanyRolePermissionMapper implements CompanyRolePermissionMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRole $companyRole
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function hydratePermissionCollection(SpyCompanyRole $companyRole, CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        $permissionCollectionTransfer = new PermissionCollectionTransfer();

        foreach ($companyRole->getSpyCompanyRoleToPermissionsJoinPermission() as $roleToPermission) {
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
