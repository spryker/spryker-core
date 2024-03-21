<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Company\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\CompanyCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Company
 * @group Business
 * @group Facade
 * @group CompanyFacadeTest
 * Add your own group annotations below this line
 */
class CompanyFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    protected const TEST_NAME = 'TEST_NAME';

    /**
     * @var string
     */
    protected const TEST_FAKE_NAME = 'TEST_FAKE_NAME';

    /**
     * @var string
     */
    protected const TEST_FAKE_ID = '777';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\Company\CompanyBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCompanyByIdShouldReturnTransfer(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::IS_ACTIVE => false,
        ]);

        // Act
        $foundCompanyTransfer = $this->tester->getFacade()
            ->getCompanyById($companyTransfer);

        // Assert
        $this->assertNotNull($foundCompanyTransfer->getIdCompany());
    }

    /**
     * @return void
     */
    public function testFindCompanyByIdReturnsTransfer(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();

        // Act
        $companyTransfer = $this->tester->getFacade()
            ->findCompanyById($companyTransfer->getIdCompany());

        // Assert
        $this->assertInstanceOf(CompanyTransfer::class, $companyTransfer);
        $this->assertNotNull($companyTransfer->getIdCompany());
    }

    /**
     * @return void
     */
    public function testFindCompanyByIdReturnsNullWhenCompanyDoesNotExists(): void
    {
        // Arrange
        $idCompany = -1;

        // Act
        $companyTransfer = $this->tester->getFacade()
            ->findCompanyById($idCompany);

        // Assert
        $this->assertNull($companyTransfer);
    }

    /**
     * @return void
     */
    public function testCreateShouldPersistCompany(): void
    {
        // Arrange
        $companyTransfer = (new CompanyBuilder())->build();

        // Act
        $createdCompanyTransfer = $this->tester->getFacade()
            ->create($companyTransfer)
            ->getCompanyTransfer();

        // Assert
        $this->assertNotNull($createdCompanyTransfer->getIdCompany());
    }

    /**
     * @return void
     */
    public function testUpdateShouldPersistCompanyChanges(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::IS_ACTIVE => false,
            CompanyTransfer::STATUS => static::STATUS_PENDING,
        ]);

        // Act
        $this->tester->getFacade()->update(
            $companyTransfer
                ->setIsActive(true)
                ->setStatus(static::STATUS_APPROVED),
        );
        $updatedCompanyTransfer = $this->tester->getFacade()->findCompanyById($companyTransfer->getIdCompany());

        // Assert
        $this->assertEquals($companyTransfer->getIsActive(), $updatedCompanyTransfer->getIsActive());
        $this->assertEquals($companyTransfer->getStatus(), $updatedCompanyTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testDeleteShouldRemoveCompanyFromStorage(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();

        // Act
        $this->tester->getFacade()
            ->delete($companyTransfer);

        // Assert
        $this->assertNull($this->tester->getFacade()->findCompanyById($companyTransfer->getIdCompany()));
    }

    /**
     * @return void
     */
    public function testCreateCompanyShouldPersistStoreRelation(): void
    {
        // Arrange
        $storeIds = [];
        foreach ($this->tester->getAllStores() as $store) {
            $storeIds[] = $store->getIdStore();
        }
        $storeRelation = (new StoreRelationBuilder([StoreRelationTransfer::ID_STORES => $storeIds]))->build();
        $companyTransfer = (new CompanyBuilder([CompanyTransfer::IS_ACTIVE => false]))->build();
        $companyTransfer->setStoreRelation($storeRelation);

        // Act
        $companyTransfer = $this->tester->getFacade()
            ->create($companyTransfer)->getCompanyTransfer();
        $relatesStores = $this->tester->getRelatedStoresByIdCompany($companyTransfer->getIdCompany());

        // Assert
        $this->assertCount(count($storeIds), $relatesStores);
    }

    /**
     * @return void
     */
    public function testUpdateCompanyShouldPersistStoreRelation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $companyTransfer = $this->tester->haveCompany();
        $storeRelation = (new StoreRelationBuilder([
            StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStoreOrFail()],
        ]))->build();
        $companyTransfer->setStoreRelation($storeRelation);

        // Act
        $companyTransfer = $this->tester->getFacade()
            ->update($companyTransfer)->getCompanyTransfer();
        $relatesStores = $this->tester->getRelatedStoresByIdCompany($companyTransfer->getIdCompany());

        // Assert
        $this->assertCount(1, $relatesStores);
    }

    /**
     * @return void
     */
    public function testGetCompaniesReturnsNotEmptyCollection(): void
    {
        // Arrange
        $this->tester->haveCompany();

        // Act
        $companyTypesCollection = $this->tester->getFacade()
            ->getCompanies();

        // Assert
        $this->assertGreaterThan(0, $companyTypesCollection->getCompanies()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyCollectionByIdCompanyShouldReturnTransferObject(): void
    {
        // Arrange
        $this->tester->haveCompany();
        $companyTransfer = $this->tester->haveCompany();

        $companyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setIdCompany($companyTransfer->getIdCompany());

        $fakeCompanyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setIdCompany(static::TEST_FAKE_ID);

        // Act
        $companyCollectionFilteredById = $this->tester->getFacade()
            ->getCompanyCollection($companyCriteriaFilterTransfer);

        $companyCollectionFilteredByFakeId = $this->tester->getFacade()
            ->getCompanyCollection($fakeCompanyCriteriaFilterTransfer);

        // Assert
        $this->assertEquals(1, $companyCollectionFilteredById->getCompanies()->count());
        $this->assertEquals(0, $companyCollectionFilteredByFakeId->getCompanies()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyCollectionByNameShouldReturnTransferObject(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::TEST_NAME,
        ]);
        $this->tester->haveCompany();

        $companyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setName($companyTransfer->getName());

        $fakeCompanyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setName(static::TEST_FAKE_NAME);

        // Act
        $companyCollectionFilteredByName = $this->tester->getFacade()
            ->getCompanyCollection($companyCriteriaFilterTransfer);

        $companyCollectionFilteredByFakeName = $this->tester->getFacade()
            ->getCompanyCollection($fakeCompanyCriteriaFilterTransfer);

        // Assert
        $this->assertEquals(1, $companyCollectionFilteredByName->getCompanies()->count());
        $this->assertEquals(0, $companyCollectionFilteredByFakeName->getCompanies()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyCollectionByLowercaseNameShouldReturnTransferObject(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::TEST_NAME,
        ]);
        $this->tester->haveCompany();

        $companyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setName(strtolower($companyTransfer->getName()));

        $fakeCompanyCriteriaFilterTransfer = (new CompanyCriteriaFilterTransfer())
            ->setName(strtolower(static::TEST_FAKE_NAME));

        // Act
        $companyCollectionFilteredByName = $this->tester->getFacade()
            ->getCompanyCollection($companyCriteriaFilterTransfer);

        $companyCollectionFilteredByFakeName = $this->tester->getFacade()
            ->getCompanyCollection($fakeCompanyCriteriaFilterTransfer);

        // Assert
        $this->assertEquals(1, $companyCollectionFilteredByName->getCompanies()->count());
        $this->assertEquals(0, $companyCollectionFilteredByFakeName->getCompanies()->count());
    }
}
