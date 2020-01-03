<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;

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
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyUnitAddressBusinessTester extends Actor
{
    use _generated\CompanyUnitAddressBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function createCompanyUnitAddressCollection(array $seedData = []): CompanyUnitAddressCollectionTransfer
    {
        return (new CompanyUnitAddressCollectionTransfer())
            ->addCompanyUnitAddress($this->haveCompanyUnitAddress($seedData));
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return int[]
     */
    public function extractAddressIdsFromCollection(CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer): array
    {
        $companyUnitAddressIds = [];
        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $companyUnitAddressIds[] = $companyUnitAddressTransfer->getIdCompanyUnitAddress();
        }

        return $companyUnitAddressIds;
    }

    /**
     * @param int $addressesAmount
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function createCompanyUnitAddressesCollection(int $addressesAmount): CompanyUnitAddressCollectionTransfer
    {
        $companyUnitAddressCollectionTransfer = new CompanyUnitAddressCollectionTransfer();
        for ($i = 0; $i < $addressesAmount; $i++) {
            $companyUnitAddressCollectionTransfer->addCompanyUnitAddress(
                $this->haveCompanyUnitAddress()
            );
        }

        return $companyUnitAddressCollectionTransfer;
    }
}
