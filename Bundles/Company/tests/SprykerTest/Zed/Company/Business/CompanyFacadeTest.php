<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Company\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;

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
class CompanyFacadeTest extends Test
{
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
        $companyTransfer = $this->tester->haveCompany(['is_active' => false]);

        // Act
        $foundCompanyTransfer = $this->getFacade()->getCompanyById($companyTransfer);

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
        $companyTransfer = $this->getFacade()->findCompanyById($companyTransfer->getIdCompany());

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
        $companyTransfer = $this->getFacade()->findCompanyById($idCompany);

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
        $createdCompanyTransfer = $this->getFacade()->create($companyTransfer)
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
            CompanyTransfer::STATUS => SpyCompanyTableMap::COL_STATUS_PENDING,
        ]);

        // Act
        $this->getFacade()->update(
            $companyTransfer
                ->setIsActive(true)
                ->setStatus(SpyCompanyTableMap::COL_STATUS_APPROVED)
        );
        $updatedCompanyTransfer = $this->tester->findCompanyById($companyTransfer->getIdCompany());

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
        $this->getFacade()->delete($companyTransfer);

        // Assert
        $this->assertNull($this->tester->findCompanyById($companyTransfer->getIdCompany()));
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
        $seed = [
            'idStores' => $storeIds,
        ];

        $storeRelation = (new StoreRelationBuilder($seed))->build();
        $companyTransfer = (new CompanyBuilder(['is_active' => false]))->build();
        $companyTransfer->setStoreRelation($storeRelation);

        // Act
        $companyTransfer = $this->getFacade()->create($companyTransfer)->getCompanyTransfer();
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
        $seed = [
            'idStores' => [$this->tester->getCurrentStore()->getIdStore()],
        ];
        $companyTransfer = $this->tester->haveCompany();
        $storeRelation = (new StoreRelationBuilder($seed))->build();
        $companyTransfer->setStoreRelation($storeRelation);

        // Act
        $companyTransfer = $this->getFacade()->update($companyTransfer)->getCompanyTransfer();
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
        $companyTypesCollection = $this->getFacade()->getCompanies();

        // Assert
        $this->assertGreaterThan(0, $companyTypesCollection->getCompanies()->count());
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
