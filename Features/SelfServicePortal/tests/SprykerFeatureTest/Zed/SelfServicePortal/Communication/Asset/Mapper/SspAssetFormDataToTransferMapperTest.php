<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Asset\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Mapper\SspAssetFormDataToTransferMapper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Asset
 * @group Mapper
 * @group SspAssetFormDataToTransferMapperTest
 */
class SspAssetFormDataToTransferMapperTest extends Unit
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Mapper\SspAssetFormDataToTransferMapper
     */
    protected SspAssetFormDataToTransferMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new SspAssetFormDataToTransferMapper();
    }

    public function testMapAssignmentsToSspAssetCollectionRequestTransferWhenAddingAndRemovingAssignments(): void
    {
        // Arrange
        $initialBusinessUnitId = 1;
        $newBusinessUnitId = 2;
        $removedBusinessUnitId = 3;

        $sspAssetTransfer = (new SspAssetTransfer())
            ->addBusinessUnitAssignment(
                (new SspAssetBusinessUnitAssignmentTransfer())
                    ->setCompanyBusinessUnit(
                        (new CompanyBusinessUnitTransfer())
                            ->setIdCompanyBusinessUnit($initialBusinessUnitId),
                    ),
            )
            ->addBusinessUnitAssignment(
                (new SspAssetBusinessUnitAssignmentTransfer())
                    ->setCompanyBusinessUnit(
                        (new CompanyBusinessUnitTransfer())
                            ->setIdCompanyBusinessUnit($removedBusinessUnitId),
                    ),
            );

        $assignedBusinessUnitIds = [$initialBusinessUnitId, $newBusinessUnitId];
        $sspAssetCollectionRequestTransfer = new SspAssetCollectionRequestTransfer();

        // Act
        $resultTransfer = $this->mapper->mapAssignmentsToSspAssetCollectionRequestTransfer(
            $assignedBusinessUnitIds,
            $sspAssetTransfer,
            $sspAssetCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $resultTransfer->getBusinessUnitAssignmentsToAdd());
        $this->assertCount(1, $resultTransfer->getBusinessUnitAssignmentsToDelete());

        $assignmentToAdd = $resultTransfer->getBusinessUnitAssignmentsToAdd()[0];
        $this->assertEquals($newBusinessUnitId, $assignmentToAdd->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail());

        $assignmentToDelete = $resultTransfer->getBusinessUnitAssignmentsToDelete()[0];
        $this->assertEquals($removedBusinessUnitId, $assignmentToDelete->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail());
    }

    public function testMapAssignmentsToSspAssetCollectionRequestTransferWhenNoChanges(): void
    {
        // Arrange
        $businessUnitId = 1;

        $sspAssetTransfer = (new SspAssetTransfer())
            ->addBusinessUnitAssignment(
                (new SspAssetBusinessUnitAssignmentTransfer())
                    ->setCompanyBusinessUnit(
                        (new CompanyBusinessUnitTransfer())
                            ->setIdCompanyBusinessUnit($businessUnitId),
                    ),
            );

        $assignedBusinessUnitIds = [$businessUnitId];
        $sspAssetCollectionRequestTransfer = new SspAssetCollectionRequestTransfer();

        // Act
        $resultTransfer = $this->mapper->mapAssignmentsToSspAssetCollectionRequestTransfer(
            $assignedBusinessUnitIds,
            $sspAssetTransfer,
            $sspAssetCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $resultTransfer->getBusinessUnitAssignmentsToAdd());
        $this->assertCount(0, $resultTransfer->getBusinessUnitAssignmentsToDelete());
    }
}
