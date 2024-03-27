<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

/**
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
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\CompanyUnitAddress\PHPMD)
 */
class CompanyUnitAddressBusinessTester extends Actor
{
    use _generated\CompanyUnitAddressBusinessTesterActions;

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
     * @return array<int>
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
                $this->haveCompanyUnitAddress(),
            );
        }

        return $companyUnitAddressCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $expectedCompanyUnitAddressCollection
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $actualCompanyUnitAddressCollection
     *
     * @return void
     */
    public function assertCompanyUnitAddressCollection(
        CompanyUnitAddressCollectionTransfer $expectedCompanyUnitAddressCollection,
        CompanyUnitAddressCollectionTransfer $actualCompanyUnitAddressCollection
    ): void {
        $this->assertCount(
            $expectedCompanyUnitAddressCollection->getCompanyUnitAddresses()->count(),
            $actualCompanyUnitAddressCollection->getCompanyUnitAddresses(),
        );

        foreach ($expectedCompanyUnitAddressCollection->getCompanyUnitAddresses() as $expectedCompanyUnitAddressTransfer) {
            $actualCompanyUnitAddressTransfer = $this->findCompanyUnitAddressTransferById(
                $actualCompanyUnitAddressCollection,
                $expectedCompanyUnitAddressTransfer->getIdCompanyUnitAddressOrFail(),
            );

            $this->assertNotNull($actualCompanyUnitAddressTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    protected function findCompanyUnitAddressTransferById(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer,
        int $idCompanyUnitAddress
    ): ?CompanyUnitAddressTransfer {
        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            if ($companyUnitAddressTransfer->getIdCompanyUnitAddressOrFail() === $idCompanyUnitAddress) {
                return $companyUnitAddressTransfer;
            }
        }

        return null;
    }
}
