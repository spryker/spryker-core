<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group SaveCompanyBusinessUnitAddressesTest
 * Add your own group annotations below this line
 */
class SaveCompanyBusinessUnitAddressesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldSaveNewAddressesAndRemoveStale(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressCollection(),
        ]);
        $companyUnitAddressCollectionTransfer = $this->tester->createCompanyUnitAddressCollection();
        $companyUnitAddressIdsNew = $this->tester->extractAddressIdsFromCollection($companyUnitAddressCollectionTransfer);
        $companyBusinessUnitTransfer->setAddressCollection($companyUnitAddressCollectionTransfer);

        // Act
        $this->tester->getFacade()
            ->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        // Assert
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection(
                (new CompanyUnitAddressCriteriaFilterTransfer())
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
            );
        $companyUnitAddressIdsActual = $this->tester->extractAddressIdsFromCollection($companyUnitAddressCollectionTransfer);

        $this->assertEquals($companyUnitAddressIdsNew, $companyUnitAddressIdsActual);
    }
}
