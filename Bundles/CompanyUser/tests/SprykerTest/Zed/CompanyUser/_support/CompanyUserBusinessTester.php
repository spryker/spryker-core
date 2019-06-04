<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;

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
class CompanyUserBusinessTester extends Actor
{
    use _generated\CompanyUserBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param array $seedData
     * @param array $companySeedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserTransfer(
        array $seedData = [
            CompanyUserTransfer::IS_ACTIVE => true,
        ],
        array $companySeedData = [
            CompanyTransfer::IS_ACTIVE => true,
            CompanyTransfer::STATUS => SpyCompanyTableMap::COL_STATUS_APPROVED,
        ]
    ): CompanyUserTransfer {
        if (!array_key_exists(CompanyUserTransfer::CUSTOMER, $seedData)) {
            $customerTransfer = $this->haveCustomer();
            $seedData = array_merge($seedData, [CompanyUserTransfer::CUSTOMER => $customerTransfer]);
        }

        if (!array_key_exists(CompanyUserTransfer::FK_COMPANY, $seedData)) {
            $companyTransfer = $this->haveCompany($companySeedData);
            $seedData = array_merge($seedData, [CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]);
        }

        return $this->haveCompanyUser($seedData);
    }
}
