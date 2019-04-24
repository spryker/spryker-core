<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Permission\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\CompanyRoleBuilder;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;
use Spryker\Zed\Permission\Business\PermissionFacadeInterface;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PermissionHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface $permissionPlugin
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function havePermission(PermissionPluginInterface $permissionPlugin): PermissionTransfer
    {
        $this->syncPermission($permissionPlugin);

        return $this->getPermissionFacade()->findPermissionByKey($permissionPlugin->getKey());
    }

    /**
     * @param \Spryker\Zed\PermissionExtension\Dependency\Plugin\PermissionStoragePluginInterface $permissionStoragePlugin
     *
     * @return void
     */
    public function preparePermissionStorageDependency(PermissionStoragePluginInterface $permissionStoragePlugin): void
    {
        $this->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [$permissionStoragePlugin]);
    }

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface $permissionPlugin
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function haveCompanyUserWithPermissions(PermissionPluginInterface $permissionPlugin, array $seedData = []): CompanyUserTransfer
    {
        $permissionTransfer = $this->havePermission($permissionPlugin);

        if (!isset($seedData[CompanyUserTransfer::FK_COMPANY])) {
            $seedData[CompanyUserTransfer::FK_COMPANY] = $this->haveCompany([
                CompanyTransfer::IS_ACTIVE => true,
                CompanyTransfer::STATUS => SpyCompanyTableMap::COL_STATUS_APPROVED,
            ])->getIdCompany();
        }

        if (!isset($seedData[CompanyUserTransfer::COMPANY_ROLE_COLLECTION])) {
            $permissionCollectionTransfer = (new PermissionCollectionTransfer())->addPermission($permissionTransfer);
            $companyRoleTransfer = $this->haveCompanyRole([
                CompanyRoleTransfer::FK_COMPANY => $seedData[CompanyUserTransfer::FK_COMPANY],
                CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
            ]);

            $seedData[CompanyUserTransfer::COMPANY_ROLE_COLLECTION] = (new CompanyRoleCollectionTransfer())->addRole($companyRoleTransfer);
        }

        if (!isset($seedData[CompanyUserTransfer::CUSTOMER])) {
            $seedData[CompanyUserTransfer::CUSTOMER] = $this->haveCustomer();
        }

        return $this->haveCompanyUserWithRoles($seedData);
    }

    /**
     * @param \Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface $permissionPlugin
     *
     * @return void
     */
    protected function syncPermission(PermissionPluginInterface $permissionPlugin): void
    {
        $this->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [$permissionPlugin]);

        $this->getPermissionFacade()->syncPermissionPlugins();
    }

    /**
     * @return \Spryker\Zed\Permission\Business\PermissionFacadeInterface
     */
    protected function getPermissionFacade(): PermissionFacadeInterface
    {
        return $this->getLocator()->permission()->facade();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function haveCompany(array $seedData = []): CompanyTransfer
    {
        $companyTransfer = (new CompanyBuilder($seedData))->build();

        return $this->getLocator()
            ->company()
            ->facade()
            ->create($companyTransfer)
            ->getCompanyTransfer();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    protected function haveCompanyRole(array $seedData = []): CompanyRoleTransfer
    {
        $companyRoleTransfer = (new CompanyRoleBuilder($seedData))->build();

        return $this->getLocator()
            ->companyRole()
            ->facade()
            ->create($companyRoleTransfer)
            ->getCompanyRoleTransfer();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function haveCustomer(array $seedData = []): CustomerTransfer
    {
        $customerTransfer = (new CustomerBuilder($seedData))->build();

        return $this->getLocator()
            ->customer()
            ->facade()
            ->addCustomer($customerTransfer)
            ->getCustomerTransfer();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function haveCompanyUserWithRoles(array $seedData = []): CompanyUserTransfer
    {
        $companyUserTransfer = (new CompanyUserBuilder($seedData))->build();
        $companyUserTransfer = $this->getLocator()
            ->companyUser()
            ->facade()
            ->create($companyUserTransfer)
            ->getCompanyUser();
        $this->assignCompanyRolesToCompanyUser($companyUserTransfer);

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    protected function assignCompanyRolesToCompanyUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $this->getLocator()
            ->companyRole()
            ->facade()
            ->saveCompanyUser($companyUserTransfer);
    }
}
