<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

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
     * @return void
     */
    public function testSaveLabelToAddressRelationsStoresDataToTheDatabase(): void
    {
        // Arrange
        $companyUnitAddressLabelCollectionTransfer = $this->tester->getCompanyUnitAddressLabelCollection();
        $companyUnitAddressTransfer = $this->tester->createCompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setLabelCollection($companyUnitAddressLabelCollectionTransfer);

        // Act
        $companyUnitAddressResponseTransfer = $this->tester->getFacade()
            ->saveLabelToAddressRelations($companyUnitAddressTransfer);

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
        $companyUnitAddressTransferRedundant = $this->tester->createCompanyUnitAddressLabelRelations()
            ->getCompanyUnitAddressTransfer();

        $companyUnitAddressTransfer = clone $companyUnitAddressTransferRedundant;
        $companyUnitAddressTransfer->setLabelCollection($this->tester->getCompanyUnitAddressLabelCollection());

        // Act
        $companyUnitAddressResponseTransfer = $this->tester->getFacade()
            ->saveLabelToAddressRelations($companyUnitAddressTransfer);

        // Assert
        $this->assertTrue($companyUnitAddressResponseTransfer->getIsSuccessful());
        $this->assertRedundantLabelsAreDeleted($companyUnitAddressTransferRedundant);
    }

    /**
     * @return void
     */
    public function testHydrateCompanyUnitAddressWithLabelCollectionHydratesTransfer(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->createCompanyUnitAddressLabelRelations()
            ->getCompanyUnitAddressTransfer();

        // Act
        $companyUnitAddressTransferHidrated = $this->tester->getFacade()
            ->hydrateCompanyUnitAddressWithLabelCollection($companyUnitAddressTransfer);

        // Assert
        $this->assertNotNull($companyUnitAddressTransferHidrated->getLabelCollection());
        $this->assertNotEmpty($companyUnitAddressTransferHidrated->getLabelCollection()->getLabels());
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressLabelsByAddressReturnsCollectionWhenRelationExists(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->createCompanyUnitAddressLabelRelations()
            ->getCompanyUnitAddressTransfer();

        // Act
        $companyUnitAddressLabelCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        // Assert
        $this->assertNotEmpty($companyUnitAddressLabelCollectionTransfer->getLabels());
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressLabelsByAddressReturnsEmptyCollectionWhenRelationDoesNotExists(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->createCompanyUnitAddressTransfer();

        // Act
        $companyUnitAddressLabelCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        // Assert
        $this->assertEmpty($companyUnitAddressLabelCollectionTransfer->getLabels());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function assertLabelsAreStored(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $originalCompanyUnitAddressIds = [];
        foreach ($companyUnitAddressTransfer->getLabelCollection()->getLabels() as $label) {
            $originalCompanyUnitAddressIds[] = $label->getIdCompanyUnitAddressLabel();
        }

        $companyUnitAddressLabelCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        $storedCompanyUnitAddressIds = [];
        foreach ($companyUnitAddressLabelCollectionTransfer->getLabels() as $companyUnitAddressLabelTransfer) {
            $storedCompanyUnitAddressIds[] = $companyUnitAddressLabelTransfer->getIdCompanyUnitAddressLabel();
        }

        // Assert
        $this->assertEquals($originalCompanyUnitAddressIds, $storedCompanyUnitAddressIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function assertRedundantLabelsAreDeleted(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $originalCompanyUnitAddressLabelIds = [];
        foreach ($companyUnitAddressTransfer->getLabelCollection()->getLabels() as $label) {
            $originalCompanyUnitAddressLabelIds[] = $label->getIdCompanyUnitAddressLabel();
        }

        $companyUnitAddressLabelCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUnitAddressLabelsByAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        $companyUnitAddressLabelIds = [];
        foreach ($companyUnitAddressLabelCollectionTransfer->getLabels() as $companyUnitAddressLabelTransfer) {
            $companyUnitAddressLabelIds[] = $companyUnitAddressLabelTransfer->getIdCompanyUnitAddressLabel();
        }

        // Assert
        $this->assertNotContains($originalCompanyUnitAddressLabelIds, $companyUnitAddressLabelIds);
    }
}
