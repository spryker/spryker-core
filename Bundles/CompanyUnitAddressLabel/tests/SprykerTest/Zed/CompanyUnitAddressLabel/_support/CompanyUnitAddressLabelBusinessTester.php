<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepository;

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
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyUnitAddressLabelBusinessTester extends Actor
{
    use _generated\CompanyUnitAddressLabelBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function getCompanyUnitAddressLabelCollection(): CompanyUnitAddressLabelCollectionTransfer
    {
        return (new CompanyUnitAddressLabelRepository())
            ->findCompanyUnitAddressLabels();
    }

    /**
     * @param array $seedData
     * @param array $companyBusinessUnitSeedData
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function createCompanyUnitAddressTransfer(array $seedData = [], array $companyBusinessUnitSeedData = []): CompanyUnitAddressTransfer
    {
        if (!isset($companyBusinessUnitSeedData[CompanyBusinessUnitTransfer::FK_COMPANY])) {
            $companyBusinessUnitSeedData[CompanyBusinessUnitTransfer::FK_COMPANY] = $this->haveCompany()->getIdCompany();
        }

        $seedData[CompanyUnitAddressTransfer::FK_COMPANY] = $this->haveCompanyBusinessUnit($companyBusinessUnitSeedData)->getFkCompany();

        return $this->haveCompanyUnitAddress($seedData);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function createCompanyUnitAddressLabelRelations(array $seedData = []): CompanyUnitAddressResponseTransfer
    {
        if (!isset($seedData[CompanyUnitAddressTransfer::LABEL_COLLECTION])) {
            $seedData[CompanyUnitAddressTransfer::LABEL_COLLECTION] = $this->getCompanyUnitAddressLabelCollection();
        }

        return $this->haveCompanyUnitAddressLabelRelations(
            $this->haveCompanyUnitAddress($seedData)->toArray(true, true)
        );
    }
}
