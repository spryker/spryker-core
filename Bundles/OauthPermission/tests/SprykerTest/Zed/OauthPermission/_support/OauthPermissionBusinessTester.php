<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthPermission;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class OauthPermissionBusinessTester extends Actor
{
    use _generated\OauthPermissionBusinessTesterActions;

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface $permissionPlugin
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function haveCompanyUserWithPermissions(PermissionPluginInterface $permissionPlugin): CompanyUserTransfer
    {
        $permissionTransfer = $this->havePermission($permissionPlugin);
        $permissionCollectionTransfer = (new PermissionCollectionTransfer())->addPermission($permissionTransfer);

        $companyTransfer = $this->haveCompany([
            CompanyTransfer::IS_ACTIVE => true,
            CompanyTransfer::STATUS => SpyCompanyTableMap::COL_STATUS_APPROVED,
        ]);
        $companyRoleTransfer = $this->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
        ]);

        $companyRoleCollection = (new CompanyRoleCollectionTransfer())->addRole($companyRoleTransfer);

        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::COMPANY_ROLE_COLLECTION => $companyRoleCollection,
        ]);

        $this->assignCompanyRolesToCompanyUser($companyUserTransfer);

        return $companyUserTransfer;
    }
}
