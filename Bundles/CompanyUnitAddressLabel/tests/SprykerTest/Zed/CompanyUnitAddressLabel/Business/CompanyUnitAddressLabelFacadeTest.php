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
     * @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected $companyUnitAddressTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->companyUnitAddressLabelFacade = $this->tester->getCompanyUnitAddressLabelFacade();
        $this->companyUnitAddressTransfer = $this->tester->getCompanyUnitAddressTransfer();
        $this->assertEmpty($this->companyUnitAddressTransfer->getLabelCollection());
    }

    /**
     * @return void
     */
    public function testSaveLabelToAddressRelationsStoresDataToTheDatabase(): void
    {
        // Arrange
        $companyUnitAddressLabelCollectionTransfer = $this->tester->haveLabelCollection();
        $this->companyUnitAddressTransfer->setLabelCollection($companyUnitAddressLabelCollectionTransfer);

        // Act
        $this->companyUnitAddressLabelFacade->saveLabelToAddressRelations($this->companyUnitAddressTransfer);

        // Assert
        $this->assertLabelsAreStored($this->companyUnitAddressTransfer);
    }

    /**
     * @return void
     */
    public function testHydrateCompanyUnitAddressWithLabelCollectionHydratesTransfer(): void
    {
        // Arrange
        $this->tester->haveLabelAddressRelations($this->tester->haveLabelCollection(), $this->companyUnitAddressTransfer);

        // Act
        $this->companyUnitAddressTransfer = $this->companyUnitAddressLabelFacade->hydrateCompanyUnitAddressWithLabelCollection($this->companyUnitAddressTransfer);

        // Assert
        $this->assertNotEmpty($this->companyUnitAddressTransfer->getLabelCollection());
        $this->assertNotEmpty($this->companyUnitAddressTransfer->getLabelCollection()->getLabels());
        foreach ($this->companyUnitAddressTransfer->getLabelCollection()->getLabels() as $label) {
            $this->assertInstanceOf(SpyCompanyUnitAddressLabelEntityTransfer::class, $label);
        }
    }

    /**
     * @return void
     */
    public function testGetCompanyUnitAddressLabelsByAddress(): void
    {
        // Arrange
        $labelCollection = $this->tester->haveLabelCollection();
        $this->tester->haveLabelAddressRelations($labelCollection, $this->companyUnitAddressTransfer);

        // Act
        $companyUnitAddressLabelCollectionTransfer = $this->companyUnitAddressLabelFacade->getCompanyUnitAddressLabelsByAddress($this->companyUnitAddressTransfer->getIdCompanyUnitAddress());

        // Assert
        $this->assertInstanceOf(CompanyUnitAddressLabelCollectionTransfer::class, $labelCollection);
        $this->assertEquals(count($labelCollection->getLabels()), count($companyUnitAddressLabelCollectionTransfer->getLabels()));
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function assertLabelsAreStored(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $labels = (array)$companyUnitAddressTransfer->getLabelCollection()->getLabels();
        $this->assertNotEmpty($labels, "No labels found in collection");
        $label = $labels[0];
        $labelAddressRelation = SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery::create()
            ->filterByFkCompanyUnitAddressLabel(
                $label->getIdCompanyUnitAddressLabel()
            )->filterByFkCompanyUnitAddress(
                $this->companyUnitAddressTransfer->getIdCompanyUnitAddress()
            )->findOne();

        $this->assertInstanceOf(SpyCompanyUnitAddressLabelToCompanyUnitAddress::class, $labelAddressRelation);
    }
}
