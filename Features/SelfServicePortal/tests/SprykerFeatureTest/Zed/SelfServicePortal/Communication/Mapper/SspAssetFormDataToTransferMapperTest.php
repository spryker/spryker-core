<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Mapper\SspAssetFormDataToTransferMapper;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Mapper
 * @group SspAssetFormDataToTransferMapperTest
 */
class SspAssetFormDataToTransferMapperTest extends Unit
{
    /**
     * @var int
     */
    protected const BUSINESS_UNIT_ID_1 = 1;

    /**
     * @var int
     */
    protected const BUSINESS_UNIT_ID_2 = 2;

    /**
     * @var int
     */
    protected const BUSINESS_UNIT_ID_3 = 3;

    /**
     * @dataProvider mapAssignmentsToSspAssetCollectionRequestTransferDataProvider
     *
     * @param array<int> $assignedBusinessUnitIds
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param array<int> $expectedAddIds
     * @param array<int> $expectedDeleteIds
     *
     * @return void
     */
    public function testMapAssignmentsToSspAssetCollectionRequestTransfer(
        array $assignedBusinessUnitIds,
        SspAssetTransfer $sspAssetTransfer,
        array $expectedAddIds,
        array $expectedDeleteIds
    ): void {
        // Arrange
        $mapper = new SspAssetFormDataToTransferMapper();
        $sspAssetCollectionRequestTransfer = new SspAssetCollectionRequestTransfer();

        // Act
        $result = $mapper->mapAssignmentsToSspAssetCollectionRequestTransfer(
            $assignedBusinessUnitIds,
            $sspAssetTransfer,
            $sspAssetCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(count($expectedAddIds), $result->getBusinessUnitAssignmentsToAdd());
        $this->assertCount(count($expectedDeleteIds), $result->getBusinessUnitAssignmentsToDelete());

        foreach ($expectedAddIds as $index => $expectedId) {
            $this->assertSame(
                $expectedId,
                $result->getBusinessUnitAssignmentsToAdd()[$index]->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
            );
        }

        foreach ($expectedDeleteIds as $index => $expectedId) {
            $this->assertSame(
                $expectedId,
                $result->getBusinessUnitAssignmentsToDelete()[$index]->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
            );
        }
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function mapAssignmentsToSspAssetCollectionRequestTransferDataProvider(): array
    {
        return [
            'No existing assignments' => [
                'assignedBusinessUnitIds' => [static::BUSINESS_UNIT_ID_1, static::BUSINESS_UNIT_ID_2],
                'sspAssetTransfer' => new SspAssetTransfer(),
                'expectedAddIds' => [static::BUSINESS_UNIT_ID_1, static::BUSINESS_UNIT_ID_2],
                'expectedDeleteIds' => [],
            ],
            'Adding and removing assignments' => [
                'assignedBusinessUnitIds' => [static::BUSINESS_UNIT_ID_1, static::BUSINESS_UNIT_ID_3],
                'sspAssetTransfer' => $this->createSspAssetTransferWithAssignments([
                    static::BUSINESS_UNIT_ID_1,
                    static::BUSINESS_UNIT_ID_2,
                ]),
                'expectedAddIds' => [static::BUSINESS_UNIT_ID_3],
                'expectedDeleteIds' => [static::BUSINESS_UNIT_ID_2],
            ],
            'No changes needed' => [
                'assignedBusinessUnitIds' => [static::BUSINESS_UNIT_ID_1, static::BUSINESS_UNIT_ID_2],
                'sspAssetTransfer' => $this->createSspAssetTransferWithAssignments([
                    static::BUSINESS_UNIT_ID_1,
                    static::BUSINESS_UNIT_ID_2,
                ]),
                'expectedAddIds' => [],
                'expectedDeleteIds' => [],
            ],
            'Assignment with null CompanyBusinessUnit' => [
                'assignedBusinessUnitIds' => [static::BUSINESS_UNIT_ID_1],
                'sspAssetTransfer' => $this->createSspAssetTransferWithNullAssignment(),
                'expectedAddIds' => [static::BUSINESS_UNIT_ID_1],
                'expectedDeleteIds' => [],
            ],
        ];
    }

    /**
     * @param array<int> $businessUnitIds
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    protected function createSspAssetTransferWithAssignments(array $businessUnitIds): SspAssetTransfer
    {
        $sspAssetTransfer = new SspAssetTransfer();

        foreach ($businessUnitIds as $businessUnitId) {
            $sspAssetTransfer->addBusinessUnitAssignment(
                (new SspAssetBusinessUnitAssignmentTransfer())
                    ->setCompanyBusinessUnit(
                        (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($businessUnitId),
                    ),
            );
        }

        return $sspAssetTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    protected function createSspAssetTransferWithNullAssignment(): SspAssetTransfer
    {
        $sspAssetTransfer = new SspAssetTransfer();
        $sspAssetTransfer->addBusinessUnitAssignment(new SspAssetBusinessUnitAssignmentTransfer());

        return $sspAssetTransfer;
    }
}
