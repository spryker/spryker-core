<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CompanyRoleBuilder;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Shared\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyRoleBusinessTester extends Actor
{
    use _generated\CompanyRoleBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param array $companyRole
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleTransfer(array $companyRole = []): CompanyRoleTransfer
    {
        return (new CompanyRoleBuilder($companyRole))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleWithPermission(array $seedData = []): CompanyRoleTransfer
    {
        if (!array_key_exists(CompanyRoleTransfer::FK_COMPANY, $seedData)) {
            $companyTransfer = $this->haveCompany();
            $seedData = array_merge($seedData, [
                CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            ]);
        }

        if (!array_key_exists(CompanyRoleTransfer::PERMISSION_COLLECTION, $seedData)) {
            $permissionCollectionTransfer = $this->getPermissionCollectionTransfer();

            $seedData = array_merge($seedData, [
                CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
            ]);
        }

        return $this->haveCompanyRole($seedData);
    }

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface|null $permissionPlugin
     * @param \Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface|null $permissionStoragePlugin
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function getPermissionCollectionTransfer(?PermissionPluginInterface $permissionPlugin = null, ?PermissionStoragePluginInterface $permissionStoragePlugin = null): PermissionCollectionTransfer
    {
        if (!$permissionPlugin) {
            $permissionPlugin = new AddCompanyUserPermissionPlugin();
        }

        if (!$permissionStoragePlugin) {
            $permissionStoragePlugin = new PermissionStoragePlugin();
        }

        $this->preparePermissionStorageDependency($permissionStoragePlugin);

        return (new PermissionCollectionTransfer())
            ->addPermission($this->havePermission($permissionPlugin));
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserWithPermission(array $seedData = []): CompanyUserTransfer
    {
        $companyTransfer = $this->haveCompany();
        $companyRoleWithPermissionTransfer = $this->getCompanyRoleWithPermission([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $companyRoleCollection = (new CompanyRoleCollectionTransfer())
            ->addRole($companyRoleWithPermissionTransfer);

        $seedData = array_merge($seedData, [
            CompanyUserTransfer::CUSTOMER => $this->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::COMPANY_ROLE_COLLECTION => $companyRoleCollection,
        ]);

        $companyUserWithPermissionTransfer = $this->haveCompanyUser($seedData);
        $this->assignCompanyRolesToCompanyUser($companyUserWithPermissionTransfer);

        return $companyUserWithPermissionTransfer;
    }
}
