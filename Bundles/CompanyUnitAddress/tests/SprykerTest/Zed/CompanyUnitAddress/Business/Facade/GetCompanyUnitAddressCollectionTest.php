<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group GetCompanyUnitAddressCollectionTest
 * Add your own group annotations below this line
 */
class GetCompanyUnitAddressCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const VALUE_COMPANY_UNIT_ADDRESSES_COUNT = 3;

    /**
     * @var int
     */
    protected const VALUE_COMPANY_UNIT_ADDRESSES_MAX_PER_PAGE = 2;

    /**
     * @var int
     */
    protected const VALUE_COMPANY_UNIT_ADDRESSES_PAGE = 2;

    /**
     * @var int
     */
    protected const VALUE_COMPANY_UNIT_ADDRESSES_COUNT_EXPECTED = 1;

    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnCollectionWhenAssigned(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressCollection(),
        ]);
        $this->tester->getFacade()
            ->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection(
                (new CompanyUnitAddressCriteriaFilterTransfer())
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
            );

        // Assert
        $this->assertGreaterThan(0, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->count());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollectionWhenNotAssigned(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection(
                (new CompanyUnitAddressCriteriaFilterTransfer())
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()),
            );

        // Assert
        $this->assertSame(0, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->count());
    }

    /**
     * @return void
     */
    public function testShouldReturnPaginatedCollectionWhenPaginationIsSet(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressesCollection(static::VALUE_COMPANY_UNIT_ADDRESSES_COUNT),
        ]);
        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        $paginationTransfer = (new PaginationTransfer())
            ->setMaxPerPage(static::VALUE_COMPANY_UNIT_ADDRESSES_MAX_PER_PAGE)
            ->setPage(static::VALUE_COMPANY_UNIT_ADDRESSES_PAGE);

        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setPagination($paginationTransfer);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()->getCompanyUnitAddressCollection(
            $companyUnitAddressCriteriaFilterTransfer,
        );

        // Assert
        $this->assertCount(
            static::VALUE_COMPANY_UNIT_ADDRESSES_COUNT_EXPECTED,
            $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses(),
        );

        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer */
        $companyUnitAddressTransfer = $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->getIterator()->current();

        $this->assertCount(
            1,
            $companyUnitAddressTransfer->getCompanyBusinessUnits()->getCompanyBusinessUnits(),
        );

        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransferLoaded */
        $companyBusinessUnitTransferLoaded = $companyUnitAddressTransfer
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
    public function testShouldReturnCompanyUnitAddressesWithCountry(): void
    {
        // Arrange
        $countryTransfer = $this->tester->haveCountry();
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::COUNTRY => $countryTransfer,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => (new CompanyUnitAddressCollectionTransfer())->addCompanyUnitAddress($companyUnitAddressTransfer),
        ]);
        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail());

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection($companyUnitAddressCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses());

        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $actualCompanyUnitAddressTransfer */
        $actualCompanyUnitAddressTransfer = $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->getIterator()->current();
        $this->assertSame($countryTransfer->toArray(), $actualCompanyUnitAddressTransfer->getCountryOrFail()->toArray());
    }
}
