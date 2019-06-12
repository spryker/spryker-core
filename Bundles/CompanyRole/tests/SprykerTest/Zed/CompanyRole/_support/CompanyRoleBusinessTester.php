<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyRole;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Shared\CompanyUser\Plugin\AddCompanyUserPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;

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
 * @method \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface getFacade()
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
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function createCompanyRoleWithPermission(array $seedData = []): CompanyRoleTransfer
    {
        if (!isset($seedData[CompanyRoleTransfer::FK_COMPANY])) {
            $companyTransfer = $this->haveCompany();
            $seedData[CompanyRoleTransfer::FK_COMPANY] = $companyTransfer->getIdCompany();
        }

        if (!isset($seedData[CompanyRoleTransfer::PERMISSION_COLLECTION])) {
            $this->preparePermissionStorageDependency(new PermissionStoragePlugin());
            $seedData[CompanyRoleTransfer::PERMISSION_COLLECTION] = (new PermissionCollectionTransfer())
                ->addPermission($this->havePermission(new AddCompanyUserPermissionPlugin()));
        }

        return $this->haveCompanyRole($seedData);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUserWithPermission(array $seedData = []): CompanyUserTransfer
    {
        $companyTransfer = $this->haveCompany();
        $companyRoleWithPermissionTransfer = $this->createCompanyRoleWithPermission([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $companyRoleCollection = (new CompanyRoleCollectionTransfer())
            ->addRole($companyRoleWithPermissionTransfer);

        $seedData[CompanyUserTransfer::CUSTOMER] = $this->haveCustomer();
        $seedData[CompanyUserTransfer::FK_COMPANY] = $companyTransfer->getIdCompany();
        $seedData[CompanyUserTransfer::COMPANY_ROLE_COLLECTION] = $companyRoleCollection;

        $companyUserWithPermissionTransfer = $this->haveCompanyUser($seedData);
        $this->assignCompanyRolesToCompanyUser($companyUserWithPermissionTransfer);

        return $companyUserWithPermissionTransfer;
    }
}
