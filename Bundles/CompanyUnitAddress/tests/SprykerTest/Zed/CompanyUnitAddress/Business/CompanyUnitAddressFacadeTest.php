<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group CompanyUnitAddressFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUnitAddressFacadeTest extends Test
{
    protected const TEST_ADDRESS = 'TEST ADDRESS';

    protected const VALUE_COMPANY_UNIT_ADDRESSES_COUNT = 3;

    protected const VALUE_COMPANY_UNIT_ADDRESSES_MAX_PER_PAGE = 2;
    protected const VALUE_COMPANY_UNIT_ADDRESSES_PAGE = 2;

    protected const VALUE_COMPANY_UNIT_ADDRESSES_COUNT_EXPECTED = 1;

    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreatePersistsDataToDatabase(): void
    {
        // Arrange
        $companyUnitAddressTransfer = (new CompanyUnitAddressBuilder())->build();

        // Act
        $companyUnitAddressTransfer = $this->tester->getFacade()
            ->create($companyUnitAddressTransfer)
            ->getCompanyUnitAddressTransfer();

        // Assert
        $this->assertNotEmpty($companyUnitAddressTransfer->getIdCompanyUnitAddress());
    }

    /**
     * @return void
     */
    public function testUpdatePersistsUpdatedDataToDatabase(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();
        $companyUnitAddressTransfer->setAddress1(static::TEST_ADDRESS);

        // Act
        $companyUnitAddressResponseTransfer = $this->tester->getFacade()
            ->update($companyUnitAddressTransfer);
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        // Assert
        $this->assertTrue($companyUnitAddressResponseTransfer->getIsSuccessful());
        $this->assertEquals(static::TEST_ADDRESS, $companyUnitAddressTransferLoaded->getAddress1());
    }

    /**
     * @return void
     */
    public function testDeleteRemovesDataFromPersistence(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();

        // Act
        $this->tester->getFacade()
            ->delete($companyUnitAddressTransfer);

        // Assert
        $this->assertNull(
            $this->tester->getFacade()
                ->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress())
        );
    }

    /**
     * @return void
     */
    public function testSaveCompanyBusinessUnitAddressesSavesNewAddressesAndRemovesStale(): void
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

        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection(
                (new CompanyUnitAddressCriteriaFilterTransfer())
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            );
        $companyUnitAddressIdsActual = $this->tester->extractAddressIdsFromCollection($companyUnitAddressCollectionTransfer);

        // Assert
        $this->assertEquals($companyUnitAddressIdsNew, $companyUnitAddressIdsActual);
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressByReturnsTransferWhenExists(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();

        // Act
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        // Assert
        $this->assertNotNull($companyUnitAddressTransferLoaded);
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressByTrowsExceptionWhenAddressNotExists(): void
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
    public function testFindCompanyUnitAddressByIdReturnsTransferWhenAddressExists(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();

        // Act
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        // Assert
        $this->assertNotNull($companyUnitAddressTransferLoaded);
    }

    /**
     * @return void
     */
    public function testFindCompanyUnitAddressByIdReturnsNullWhenAddressNotExists(): void
    {
        // Arrange
        $idCompanyUnitAddress = 0;

        // Act
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->findCompanyUnitAddressById($idCompanyUnitAddress);

        // Assert
        $this->assertNull($companyUnitAddressTransferLoaded);
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressCollectionReturnsCollectionWhenAssigned(): void
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
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            );

        // Assert
        $this->assertGreaterThan(0, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressCollectionReturnsEmptyCollectionWhenNotAssigned(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        // Act
        $companyUnitAddressCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressCollection(
                (new CompanyUnitAddressCriteriaFilterTransfer())
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            );

        // Assert
        $this->assertEquals(0, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressCollectionReturnsPaginatedCompanyUnitAddressesWhenPaginationIsSet(): void
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
            $companyUnitAddressCriteriaFilterTransfer
        );

        // Assert
        $this->assertCount(
            static::VALUE_COMPANY_UNIT_ADDRESSES_COUNT_EXPECTED,
            $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()
        );
    }
}
