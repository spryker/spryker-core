<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyRoleDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

/**
 * Inherited Methods
 *
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
class CompanyRoleDataImportCommunicationTester extends Actor
{
    protected const COMPANY_KEY_1 = 'Test_ltd';
    protected const COMPANY_KEY_2 = 'Test2_ltd';
    protected const COMPANY_ROLE_ADMIN = 'Role_Admin';
    protected const COMPANY_ROLE_BUYER = 'Role_Buyer';
    protected const COMPANY_USER_KEY_1 = 'ComUser--1';
    protected const COMPANY_USER_KEY_2 = 'ComUser--2';
    protected const PERMISSION_PLUGINS = [
        MockPermissionPlugin::class,
    ];

    use _generated\CompanyRoleDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function prepareTestData(): void
    {
        $customerTransfer = $this->haveCustomer();

        $companyTransfer1 = $this->haveCompany([
            CompanyTransfer::KEY => static::COMPANY_KEY_1,
        ]);

        $companyTransfer2 = $this->haveCompany([
            CompanyTransfer::KEY => static::COMPANY_KEY_2,
        ]);

        $this->haveCompanyUser([
            CompanyUserTransfer::KEY => static::COMPANY_USER_KEY_1,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer1->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
        ]);

        $this->haveCompanyUser([
            CompanyUserTransfer::KEY => static::COMPANY_USER_KEY_2,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer1->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
        ]);

        $this->haveCompanyRole([
            CompanyRoleTransfer::KEY => static::COMPANY_ROLE_ADMIN,
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer1->getIdCompany(),
        ]);

        $this->haveCompanyRole([
            CompanyRoleTransfer::KEY => static::COMPANY_ROLE_BUYER,
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer2->getIdCompany(),
        ]);

        foreach (static::PERMISSION_PLUGINS as $permissionPlugin) {
            $this->havePermission(new $permissionPlugin());
        }
    }
}
