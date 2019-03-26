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
        $companyUnitAddressTransferRedundant = $this->haveCompanyUnitAddressLabelRelations(
            clone $companyUnitAddressTransfer,
            $this->tester->buildCompanyUnitAddressLabelCollection(['name' => 'should be deleted'])
        );

        $companyUnitAddressLabelCollectionTransfer = $this->tester->buildCompanyUnitAddressLabelCollection(['name' => 'should stay']);
        $companyUnitAddressTransfer->setLabelCollection($companyUnitAddressLabelCollectionTransfer);

        // Act
        $companyUnitAddressResponseTransfer = $this->companyUnitAddressLabelFacade->saveLabelToAddressRelations($companyUnitAddressTransfer);

        // Assert
        $this->assertTrue($companyUnitAddressResponseTransfer->getIsSuccessful());
        $this->assertLabelsAreStored($companyUnitAddressTransfer);
        $this->assertRedundantLabelsAreDeleted($companyUnitAddressTransferRedundant);
    }

    /**
     * @return void
     */
    public function testHydrateCompanyUnitAddressWithLabelCollectionHydratesTransfer(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->getCompanyUnitAddressTransfer();
        $this->haveCompanyUnitAddressLabelRelations(
            clone $companyUnitAddressTransfer,
            $this->tester->buildCompanyUnitAddressLabelCollection()
        );

        // Act
        $companyUnitAddressTransferHidrated = $this->companyUnitAddressLabelFacade->hydrateCompanyUnitAddressWithLabelCollection($companyUnitAddressTransfer);

        // Assert
        $this->assertNotEmpty($companyUnitAddressTransferHidrated->getLabelCollection());
        $this->assertNotEmpty($companyUnitAddressTransferHidrated->getLabelCollection()->getLabels());
        foreach ($companyUnitAddressTransferHidrated->getLabelCollection()->getLabels() as $label) {
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

        $companyUnitAddressLabelCollectionTransferOriginnal = $this->tester->buildCompanyUnitAddressLabelCollection();
        $this->haveCompanyUnitAddressLabelRelations(clone $companyUnitAddressTransfer, $companyUnitAddressLabelCollectionTransferOriginnal);

        // Act
        $companyUnitAddressLabelCollectionTransfer = $this->companyUnitAddressLabelFacade->getCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        // Assert
        $this->assertInstanceOf(CompanyUnitAddressLabelCollectionTransfer::class, $companyUnitAddressLabelCollectionTransfer);
        $this->assertEquals(count($companyUnitAddressLabelCollectionTransferOriginnal->getLabels()), count($companyUnitAddressLabelCollectionTransfer->getLabels()));
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

        $companyUnitAddressLabelCollectionTransfer = $this->companyUnitAddressLabelFacade->getCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        $storedCompanyUnitAddressIds = [];
        foreach ($companyUnitAddressLabelCollectionTransfer->getLabels() as $companyUnitAddressLabelTransfer) {
            $this->assertInstanceOf(SpyCompanyUnitAddressLabelEntityTransfer::class, $companyUnitAddressLabelTransfer);
            $storedCompanyUnitAddressIds[] = $companyUnitAddressLabelTransfer->getIdCompanyUnitAddressLabel();
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

        $originalCompanyUnitAddressLabelIds = [];
        foreach ($labels as $label) {
            $originalCompanyUnitAddressLabelIds[] = $label->getIdCompanyUnitAddressLabel();
        }

        $companyUnitAddressLabelCollectionTransfer = $this->companyUnitAddressLabelFacade->getCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        $companyUnitAddressLabelIds = [];
        foreach ($companyUnitAddressLabelCollectionTransfer->getLabels() as $companyUnitAddressLabelTransfer) {
            $companyUnitAddressLabelIds[] = $companyUnitAddressLabelTransfer->getIdCompanyUnitAddressLabel();
        }

        $this->assertNotContains($originalCompanyUnitAddressLabelIds, $companyUnitAddressLabelIds);
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

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer $companyUnitAddressLabelCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected function haveCompanyUnitAddressLabelRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer, $companyUnitAddressLabelCollectionTransfer): CompanyUnitAddressTransfer
    {
        $companyUnitAddressTransfer->setLabelCollection($companyUnitAddressLabelCollectionTransfer);
        $this->tester->haveCompanyUnitAddressLabelRelations($companyUnitAddressTransfer);

        return $companyUnitAddressTransfer;
    }
}
