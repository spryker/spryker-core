<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SharedCartBusinessTester extends Actor
{
    use _generated\SharedCartBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuote(CustomerTransfer $customerTransfer)
    {
        return $this->havePersistentQuote(
            [
                QuoteTransfer::CUSTOMER => $customerTransfer,
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUser(CustomerTransfer $customerTransfer)
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
    public function createCompany()
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
    public function createCompanyBusinessUnit($companyTransfer)
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
    public function createShareCartDetail(int $idCompanyUser, $permissionQuoteGroup): ShareDetailTransfer
    {
        $shareDetailTransfer = new ShareDetailTransfer();
        $shareDetailTransfer->setIdCompanyUser($idCompanyUser);
        $shareDetailTransfer->setQuotePermissionGroup($permissionQuoteGroup);

        return $shareDetailTransfer;
    }
}
