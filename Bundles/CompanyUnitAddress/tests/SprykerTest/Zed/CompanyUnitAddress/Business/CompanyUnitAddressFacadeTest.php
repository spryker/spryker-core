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
        $companyUnitAddressTransfer = $this->getFacade()->create($companyUnitAddressTransfer)
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
        $companyUnitAddressResponseTransfer = $this->getFacade()->update($companyUnitAddressTransfer);
        $companyUnitAddressTransferLoaded = $this->getFacade()->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress());

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
        $this->getFacade()->delete($companyUnitAddressTransfer);

        // Assert
        $this->assertNull($this->getFacade()->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress()));
    }

    /**
     * @return void
     */
    public function testSaveCompanyBusinessUnitAddressesSavesNewAddressesAndRemovesStale(): void
    {
        // Arrange
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->getCompanyUnitAddressCollection(),
        ]);
        $companyUnitAddressCollectionTransfer = $this->tester->getCompanyUnitAddressCollection();
        $companyUnitAddressIdsNew = $this->tester->extractAddressIdsFromCollection($companyUnitAddressCollectionTransfer);
        $companyBusinessUnitTransfer->setAddressCollection($companyUnitAddressCollectionTransfer);

        // Act
        $this->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        $companyUnitAddressCollectionTransfer = $this->getFacade()->getCompanyUnitAddressCollection(
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
        $companyUnitAddressTransferLoaded = $this->getFacade()->getCompanyUnitAddressById($companyUnitAddressTransfer);

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
        $this->getFacade()->getCompanyUnitAddressById($companyUnitAddressTransfer);
    }

    /**
     * @return void
     */
    public function testFindCompanyUnitAddressByIdReturnsTransferWhenAddressExists(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();

        // Act
        $companyUnitAddressTransferLoaded = $this->getFacade()->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress());

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
        $companyUnitAddressTransferLoaded = $this->getFacade()->findCompanyUnitAddressById($idCompanyUnitAddress);

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
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->getCompanyUnitAddressCollection(),
        ]);
        $this->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);

        // Act
        $companyUnitAddressCollectionTransfer = $this->getFacade()->getCompanyUnitAddressCollection(
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
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit();

        // Act
        $companyUnitAddressCollectionTransfer = $this->getFacade()->getCompanyUnitAddressCollection(
            (new CompanyUnitAddressCriteriaFilterTransfer())
                ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
        );

        // Assert
        $this->assertEquals(0, $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses()->count());
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacade|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
