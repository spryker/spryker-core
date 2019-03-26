<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddress;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddressLabel
 * @group Business
 * @group Facade
 * @group CompanyUnitAddressLabelFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUnitAddressLabelFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddressLabel\CompanyUnitAddressLabelBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface
     */
    protected $companyUnitAddressLabelFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->companyUnitAddressLabelFacade = $this->tester->getCompanyUnitAddressLabelFacade();
    }

    /**
     * @return void
     */
    public function testSaveLabelToAddressRelationsStoresDataToTheDatabase(): void
    {
        // Arrange
        $companyUnitAddressLabelCollectionTransfer = $this->tester->buildCompanyUnitAddressLabelCollection();

        $companyUnitAddressTransfer = $this->getCompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setLabelCollection($companyUnitAddressLabelCollectionTransfer);

        // Act
        $companyUnitAddressResponseTransfer = $this->companyUnitAddressLabelFacade->saveLabelToAddressRelations($companyUnitAddressTransfer);

        // Assert
        $this->assertTrue($companyUnitAddressResponseTransfer->getIsSuccessful());
        $this->assertLabelsAreStored($companyUnitAddressTransfer);
    }

    /**
     * @return void
     */
    public function testSaveLabelToAddressRelationsRemovesRedundantRelations(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->getCompanyUnitAddressTransfer();

        $StaleCompanyUnitAddressLabelCollectionTransfer = $this->tester->buildCompanyUnitAddressLabelCollection(['name' => 'should be deleted']);
        $redundantCompanyUnitAddressTransfer = clone $companyUnitAddressTransfer;
        $redundantCompanyUnitAddressTransfer->setLabelCollection($StaleCompanyUnitAddressLabelCollectionTransfer);
        $this->tester->haveCompanyUnitAddressLabelRelations($redundantCompanyUnitAddressTransfer);

        $this->assertEmpty($companyUnitAddressTransfer->getLabelCollection());

        $companyUnitAddressLabelCollectionTransfer = $this->tester->buildCompanyUnitAddressLabelCollection(['name' => 'should stay']);
        $companyUnitAddressTransfer->setLabelCollection($companyUnitAddressLabelCollectionTransfer);

        // Act
        $companyUnitAddressResponseTransfer = $this->companyUnitAddressLabelFacade->saveLabelToAddressRelations($companyUnitAddressTransfer);

        // Assert
        $this->assertTrue($companyUnitAddressResponseTransfer->getIsSuccessful());
        $this->assertLabelsAreStored($companyUnitAddressTransfer);
        $this->assertRedundantLabelsAreDeleted($redundantCompanyUnitAddressTransfer);
    }

    /**
     * @return void
     */
    public function testHydrateCompanyUnitAddressWithLabelCollectionHydratesTransfer(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->getCompanyUnitAddressTransfer();

        $companyUnitAddressLabelCollectionTransfer = $this->tester->buildCompanyUnitAddressLabelCollection();
        $copyOfcompanyUnitAddressTransfer = clone $companyUnitAddressTransfer;
        $copyOfcompanyUnitAddressTransfer->setLabelCollection($companyUnitAddressLabelCollectionTransfer);
        $this->tester->haveCompanyUnitAddressLabelRelations($copyOfcompanyUnitAddressTransfer);
        $this->assertEmpty($companyUnitAddressTransfer->getLabelCollection());

        // Act
        $companyUnitAddressTransfer = $this->companyUnitAddressLabelFacade->hydrateCompanyUnitAddressWithLabelCollection($companyUnitAddressTransfer);

        // Assert
        $this->assertNotEmpty($companyUnitAddressTransfer->getLabelCollection());
        $this->assertNotEmpty($companyUnitAddressTransfer->getLabelCollection()->getLabels());
        foreach ($companyUnitAddressTransfer->getLabelCollection()->getLabels() as $label) {
            $this->assertInstanceOf(SpyCompanyUnitAddressLabelEntityTransfer::class, $label);
        }
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressLabelsByAddress(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->getCompanyUnitAddressTransfer();

        $OriginCompanyUnitAddressLabelCollectionTransfer = $this->tester->buildCompanyUnitAddressLabelCollection();
        $copyOfcompanyUnitAddressTransfer = clone $companyUnitAddressTransfer;
        $copyOfcompanyUnitAddressTransfer->setLabelCollection($OriginCompanyUnitAddressLabelCollectionTransfer);
        $this->tester->haveCompanyUnitAddressLabelRelations($copyOfcompanyUnitAddressTransfer);

        // Act
        $companyUnitAddressLabelCollectionTransfer = $this->companyUnitAddressLabelFacade->getCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        // Assert
        $this->assertInstanceOf(CompanyUnitAddressLabelCollectionTransfer::class, $companyUnitAddressLabelCollectionTransfer);
        $this->assertEquals(count($OriginCompanyUnitAddressLabelCollectionTransfer->getLabels()), count($companyUnitAddressLabelCollectionTransfer->getLabels()));
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function assertLabelsAreStored(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $labels = (array)$companyUnitAddressTransfer->getLabelCollection()->getLabels();
        $this->assertGreaterThan(0, count($labels), "No labels found in collection");

        $originalCompanyUnitAddressIds = [];
        foreach ($labels as $label) {
            $originalCompanyUnitAddressIds[] = $label->getIdCompanyUnitAddressLabel();
        }

        $labelAddressRelationCollection = SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery::create()
            ->filterByFkCompanyUnitAddress(
                $companyUnitAddressTransfer->getIdCompanyUnitAddress()
            )->find();

        $storedCompanyUnitAddressIds = [];
        foreach ($labelAddressRelationCollection as $labelAddressRelation) {
            $this->assertInstanceOf(SpyCompanyUnitAddressLabelToCompanyUnitAddress::class, $labelAddressRelation);
            $storedCompanyUnitAddressIds[] = $labelAddressRelation->getFkCompanyUnitAddressLabel();
        }
        $this->assertEquals($originalCompanyUnitAddressIds, $storedCompanyUnitAddressIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function assertRedundantLabelsAreDeleted(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $labels = (array)$companyUnitAddressTransfer->getLabelCollection()->getLabels();
        $this->assertGreaterThan(0, count($labels), "No labels found in collection");

        $originalCompanyUnitAddressIds = [];
        foreach ($labels as $label) {
            $originalCompanyUnitAddressIds[] = $label->getIdCompanyUnitAddressLabel();
        }

        $labelAddressRelationCollection = SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery::create()
            ->filterByFkCompanyUnitAddressLabel_In($originalCompanyUnitAddressIds)
            ->filterByFkCompanyUnitAddress(
                $companyUnitAddressTransfer->getIdCompanyUnitAddress()
            )->find();

        $this->assertCount(0, $labelAddressRelationCollection);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected function getCompanyUnitAddressTransfer(): CompanyUnitAddressTransfer
    {
        $companyBusinessUnitWithCompany = $this->tester->haveCompanyBusinessUnitWithCompany();
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer */
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress([
            'fkCompany' => $companyBusinessUnitWithCompany->getFkCompany(),
        ]);

        return $companyUnitAddressTransfer;
    }
}
