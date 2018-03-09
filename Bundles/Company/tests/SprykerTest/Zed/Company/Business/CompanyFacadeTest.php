<?php

namespace SprykerTest\Zed\Company\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Spryker\Zed\Company\Persistence\CompanyRepository;

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
    public function testGetCompanyByIdShouldReturnTransfer()
    {
        $companyTransfer = $this->tester->haveCompany(['is_active' => false]);
        $foundCompanyTransfer = $this->getFacade()->getCompanyById($companyTransfer);
        $this->assertNotNull($foundCompanyTransfer->getIdCompany());
    }

    /**
     * @return void
     */
    public function testCreateShouldPersistCompany()
    {
        $companyTransfer = (new CompanyBuilder())->build();
        $createdCompanyTransfer = $this->getFacade()->create($companyTransfer)->getCompanyTransfer();

        $this->assertNotNull($createdCompanyTransfer->getIdCompany());
    }

    /**
     * @return void
     */
    public function testUpdateShouldPersistCompanyChanges()
    {
        $companyTransfer = $this->tester->haveCompany(['is_active' => false]);

        $companyTransfer->setIsActive(true);
        $companyTransfer->setStatus(SpyCompanyTableMap::COL_STATUS_APPROVED);
        $this->getFacade()->update($companyTransfer)->getCompanyTransfer();

        $updatedCompanyTransfer = $this->tester->findCompanyById($companyTransfer->getIdCompany());

        $this->assertEquals($companyTransfer->getIsActive(), $updatedCompanyTransfer->getIsActive());
        $this->assertEquals($companyTransfer->getStatus(), $updatedCompanyTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testDeleteShouldRemoveCompanyFromStorage()
    {
        $companyTransfer = $this->tester->haveCompany(['is_active' => false]);
        $this->getFacade()->delete($companyTransfer);
        $this->assertNull($this->tester->findCompanyById($companyTransfer->getIdCompany()));
    }

    /**
     * @return void
     */
    public function testCreateOrUpdateCompanyShouldPersistStoreRelation()
    {
        $storeIds = [];
        foreach ($this->getAllStores() as $store) {
            $storeIds[] = $store->getIdStore();
        }
        $seed = [
            'idStores' => $storeIds,
        ];

        $storeRelation = (new StoreRelationBuilder($seed))->build();
        $companyTransfer = (new CompanyBuilder(['is_active' => false]))->build();
        $companyTransfer->setStoreRelation($storeRelation);
        $companyTransfer = $this->getFacade()->create($companyTransfer)->getCompanyTransfer();
        $relatesStores = (new CompanyRepository())->getRelatedStoresByCompanyId($companyTransfer->getIdCompany());
        $this->assertCount(count($storeIds), $relatesStores);

        $seed = [
            'idStores' => [$this->getCurrentStore()->getIdStore()],
        ];

        $storeRelation = (new StoreRelationBuilder($seed))->build();
        $companyTransfer->setStoreRelation($storeRelation);
        $companyTransfer = $this->getFacade()->update($companyTransfer)->getCompanyTransfer();
        $relatesStores = (new CompanyRepository())->getRelatedStoresByCompanyId($companyTransfer->getIdCompany());
        $this->assertCount(1, $relatesStores);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore()
    {
        return $this->tester->getLocator()->store()->facade()->getCurrentStore();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getAllStores()
    {
        return $this->tester->getLocator()->store()->facade()->getAllStores();
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
