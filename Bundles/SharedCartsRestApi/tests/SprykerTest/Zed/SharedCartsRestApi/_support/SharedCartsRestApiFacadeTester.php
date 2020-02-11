<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCartsRestApi;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\SharedCart\Communication\Plugin\QuotePermissionStoragePlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SharedCartsRestApiFacadeTester extends Actor
{
    use _generated\SharedCartsRestApiFacadeTesterActions;

    /**
     * @return void
     */
    public function setPermissionDependencies(): void
    {
        $this->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new QuotePermissionStoragePlugin(),
        ]);

        $this->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new ReadSharedCartPermissionPlugin(),
            new WriteSharedCartPermissionPlugin(),
        ]);

        $this->getLocator()->permission()->facade()->syncPermissionPlugins();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUser(CustomerTransfer $customerTransfer): CompanyUserTransfer
    {
        $companyTransfer = $this->createCompany();
        $companyBusinessUnit = $this->createCompanyBusinessUnit($companyTransfer);

        return $this->haveCompanyUser(
            [
                CompanyUserTransfer::CUSTOMER => $customerTransfer,
                CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnit->getIdCompanyBusinessUnit(),
                CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            ]
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function createCompany(): CompanyTransfer
    {
        return $this->haveCompany(
            [
                CompanyTransfer::NAME => 'Test company',
                CompanyTransfer::STATUS => 'approved',
                CompanyTransfer::IS_ACTIVE => true,
                CompanyTransfer::INITIAL_USER_TRANSFER => new CompanyUserTransfer(),
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function createCompanyBusinessUnit(CompanyTransfer $companyTransfer): CompanyBusinessUnitTransfer
    {
        return $this->haveCompanyBusinessUnit(
            [
                CompanyBusinessUnitTransfer::NAME => 'test business unit',
                CompanyBusinessUnitTransfer::EMAIL => 'test@spryker.com',
                CompanyBusinessUnitTransfer::PHONE => '1234567890',
                CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            ]
        );
    }

    /**
     * @param int $idCompanyUser
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $permissionQuoteGroup
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer
     */
    public function createShareCartDetail(int $idCompanyUser, QuotePermissionGroupTransfer $permissionQuoteGroup): ShareDetailTransfer
    {
        $shareDetailTransfer = new ShareDetailTransfer();
        $shareDetailTransfer->setIdCompanyUser($idCompanyUser);
        $shareDetailTransfer->setQuotePermissionGroup($permissionQuoteGroup);

        return $shareDetailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomerWithCompanyUser(CompanyTransfer $companyTransfer): CustomerTransfer
    {
        $customerTransfer = $this->haveCustomer();
        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $ownerCustomerTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function haveSharedQuote(
        CustomerTransfer $ownerCustomerTransfer,
        CompanyUserTransfer $companyUserTransfer,
        SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
    ): QuoteTransfer {
        $quoteTransfer = $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $this->haveQuoteCompanyUser(
            $companyUserTransfer,
            $quoteTransfer,
            $quotePermissionGroupEntityTransfer
        );

        return $quoteTransfer;
    }
}
