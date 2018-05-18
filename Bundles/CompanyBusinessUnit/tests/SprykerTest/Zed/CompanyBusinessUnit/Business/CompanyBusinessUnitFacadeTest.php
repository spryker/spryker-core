<?php

namespace SprykerTest\Zed\CompanyBusinessUnit\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use TypeError;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group Facade
 * @group CompanyBusinessUnitFacadeTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateShouldPersistCompanyBusinessUnit()
    {
        $idCompany = $this->tester->haveCompany()->getIdCompany();

        $seedData = [
            'fkCompany' => $idCompany,
            'idCompanyBusinessUnit' => null,
        ];

        $businessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();

        $createdTransfer = $this->getFacade()
            ->create($businessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        $this->assertNotNull($createdTransfer->getIdCompanyBusinessUnit());
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitByIdShouldReturnTransferObject()
    {
        $idCompany = $this->tester->haveCompany()->getIdCompany();

        $seedData = [
            'fkCompany' => $idCompany,
            'idCompanyBusinessUnit' => null,
        ];

        $businessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();

        $createdBusinessUnitTransfer = $this->getFacade()
            ->create($businessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        $foundBusinessUnitTransfer = $this->getFacade()->getCompanyBusinessUnitById($createdBusinessUnitTransfer);
        $this->assertSame($createdBusinessUnitTransfer->getName(), $foundBusinessUnitTransfer->getName());
    }

    /**
     * @return void
     */
    public function testUpdateShouldPersistCompanyBusinessUnitChanges()
    {
        $idCompany = $this->tester->haveCompany()->getIdCompany();

        $seedData = [
            'fkCompany' => $idCompany,
            'idCompanyBusinessUnit' => null,
        ];

        $businessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();

        $createdBusinessUnitTransfer = $this->getFacade()
            ->create(clone $businessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        $createdBusinessUnitTransfer->setName($createdBusinessUnitTransfer->getName() . 'TEST');
        $updatedBusinessUnitTransfer = $this->getFacade()->update($createdBusinessUnitTransfer)->getCompanyBusinessUnitTransfer();

        $this->assertNotSame($businessUnitTransfer->getName(), $updatedBusinessUnitTransfer->getName());
    }

    /**
     * @return void
     */
    public function testDeleteShouldRemoveCompanyBusinessUnitFromStorage()
    {
        $idCompany = $this->tester->haveCompany()->getIdCompany();

        $seedData = [
            'fkCompany' => $idCompany,
            'idCompanyBusinessUnit' => null,
        ];

        $businessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();

        $createdBusinessUnitTransfer = $this->getFacade()
            ->create(clone $businessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        $this->getFacade()->delete($createdBusinessUnitTransfer);
        $this->expectException(TypeError::class);
        $this->getFacade()->getCompanyBusinessUnitById($createdBusinessUnitTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteShouldClearParentForChildrenBusinessUnit()
    {
        // Arrange
        $idCompany = $this->tester->haveCompany()->getIdCompany();
        $seedData = [
            'fkCompany' => $idCompany,
            'idCompanyBusinessUnit' => null,
        ];
        $businessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();
        $businessUnitTransfer = $this->getFacade()
            ->create($businessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();
        $seedData = [
            'fkCompany' => $idCompany,
            'idCompanyBusinessUnit' => null,
            'idParentCompanyBusinessUnit' => $businessUnitTransfer->getIdCompanyBusinessUnit(),
        ];
        $childBusinessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();
        $childBusinessUnitTransfer = $this->getFacade()
            ->create($childBusinessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        // Act
        $this->getFacade()->delete($businessUnitTransfer);
        $loadedChildBusinessUnitTransfer = $this->getFacade()
            ->getCompanyBusinessUnitById($childBusinessUnitTransfer);

        // Assert
        $this->assertNull($loadedChildBusinessUnitTransfer->getParentCompanyBusinessUnit()->getIdParentCompanyBusinessUnit());
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitCollectionshouldReturnTransferObject()
    {
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
