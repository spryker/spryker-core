<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

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
 * @method \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ShoppingListBusinessTester extends Actor
{
    use _generated\ShoppingListBusinessTesterActions;

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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingList(CompanyUserTransfer $companyUserTransfer): ShoppingListTransfer
    {
        return $this->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $companyUserTransfer->getCustomer()->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
        ]);
    }
}
