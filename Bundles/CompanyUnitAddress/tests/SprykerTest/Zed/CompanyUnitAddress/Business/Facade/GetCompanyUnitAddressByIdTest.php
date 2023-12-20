<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group GetCompanyUnitAddressByIdTest
 * Add your own group annotations below this line
 */
class GetCompanyUnitAddressByIdTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnCompanyUnitAddressWhenExists(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();

        // Act
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        // Assert
        $this->assertEquals(
            $companyUnitAddressTransfer->getIdCompanyUnitAddress(),
            $companyUnitAddressTransferLoaded->getIdCompanyUnitAddress(),
        );

        $this->assertCount(
            0,
            $companyUnitAddressTransferLoaded->getCompanyBusinessUnits()->getCompanyBusinessUnits(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnCompanyUnitAddressWithCompanyBusinessUnitWhenMapped(): void
    {
        // Arrange
        $companyUnitAddressCollection = $this->tester->createCompanyUnitAddressCollection();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $companyUnitAddressCollection,
        ]);
        $this->tester->haveCompanyUnitAddressToCompanyBusinessUnitRelation($companyBusinessUnitTransfer);

        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer */
        $companyUnitAddressTransfer = $companyUnitAddressCollection->getCompanyUnitAddresses()->getIterator()->current();

        // Act
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        // Assert
        $this->assertCount(
            1,
            $companyUnitAddressTransferLoaded->getCompanyBusinessUnits()->getCompanyBusinessUnits(),
        );

        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransferLoaded */
        $companyBusinessUnitTransferLoaded = $companyUnitAddressTransferLoaded
            ->getCompanyBusinessUnits()
            ->getCompanyBusinessUnits()
            ->getIterator()
            ->current();

        $this->assertEquals(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            $companyBusinessUnitTransferLoaded->getIdCompanyBusinessUnit(),
        );
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenCompanyUnitAddressDoesNotExist(): void
    {
        // Arrange
        $companyUnitAddressTransfer = (new CompanyUnitAddressBuilder())->build();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnCompanyUnitAddressWithCountry(): void
    {
        // Arrange
        $this->tester->haveCompanyUnitAddress();

        $countryTransfer = $this->tester->haveCountry();
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::COUNTRY => $countryTransfer,
        ]);

        // Act
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        // Assert
        $this->assertSame($companyUnitAddressTransfer->getIdCompanyUnitAddressOrFail(), $companyUnitAddressTransferLoaded->getIdCompanyUnitAddressOrFail());
        $this->assertSame($countryTransfer->toArray(), $companyUnitAddressTransferLoaded->getCountryOrFail()->toArray());
    }
}
