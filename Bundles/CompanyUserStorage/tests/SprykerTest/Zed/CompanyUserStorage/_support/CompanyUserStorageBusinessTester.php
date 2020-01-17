<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyUserStorageBusinessTester extends Actor
{
    use _generated\CompanyUserStorageBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param bool $isActiveCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function haveCompanyUserTransfer(bool $isActiveCompany = true): CompanyUserTransfer
    {
        $companyTransfer = $this->haveCompany([
            CompanyTransfer::IS_ACTIVE => $isActiveCompany,
            CompanyTransfer::STATUS => SpyCompanyTableMap::COL_STATUS_APPROVED,
        ]);
        $customerTransfer = $this->haveCustomer();

        return $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::COMPANY => $companyTransfer,
            CompanyUserTransfer::IS_ACTIVE => true,
        ]);
    }

    /**
     * @return void
     */
    public function ensureCompanyUserDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getCompanyUserQuery());
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return SpyCompanyUserQuery::create();
    }
}
