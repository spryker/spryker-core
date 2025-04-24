<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspAssetManagement\Communication\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SspAssetAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Zed\SspAssetManagement\Communication\Mapper\SspAssetFormDataToTransferMapper;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspAssetManagement
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
     * @param array<int> $expectedRemoveIds
     *
     * @return void
     */
    public function testMapAssignmentsToSspAssetCollectionRequestTransfer(
        array $assignedBusinessUnitIds,
        SspAssetTransfer $sspAssetTransfer,
        array $expectedAddIds,
        array $expectedRemoveIds
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
        $this->assertCount(count($expectedAddIds), $result->getAssignmentsToAdd());
        $this->assertCount(count($expectedRemoveIds), $result->getAssignmentsToRemove());

        foreach ($expectedAddIds as $index => $expectedId) {
            $this->assertSame(
                $expectedId,
                $result->getAssignmentsToAdd()[$index]->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
            );
        }

        foreach ($expectedRemoveIds as $index => $expectedId) {
            $this->assertSame(
                $expectedId,
                $result->getAssignmentsToRemove()[$index]->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
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
                'expectedRemoveIds' => [],
            ],
            'Adding and removing assignments' => [
                'assignedBusinessUnitIds' => [static::BUSINESS_UNIT_ID_1, static::BUSINESS_UNIT_ID_3],
                'sspAssetTransfer' => $this->createSspAssetTransferWithAssignments([
                    static::BUSINESS_UNIT_ID_1,
                    static::BUSINESS_UNIT_ID_2,
                ]),
                'expectedAddIds' => [static::BUSINESS_UNIT_ID_3],
                'expectedRemoveIds' => [static::BUSINESS_UNIT_ID_2],
            ],
            'No changes needed' => [
                'assignedBusinessUnitIds' => [static::BUSINESS_UNIT_ID_1, static::BUSINESS_UNIT_ID_2],
                'sspAssetTransfer' => $this->createSspAssetTransferWithAssignments([
                    static::BUSINESS_UNIT_ID_1,
                    static::BUSINESS_UNIT_ID_2,
                ]),
                'expectedAddIds' => [],
                'expectedRemoveIds' => [],
            ],
            'Assignment with null CompanyBusinessUnit' => [
                'assignedBusinessUnitIds' => [static::BUSINESS_UNIT_ID_1],
                'sspAssetTransfer' => $this->createSspAssetTransferWithNullAssignment(),
                'expectedAddIds' => [static::BUSINESS_UNIT_ID_1],
                'expectedRemoveIds' => [],
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
            $sspAssetTransfer->addAssignment(
                (new SspAssetAssignmentTransfer())
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
        $sspAssetTransfer->addAssignment(new SspAssetAssignmentTransfer());

        return $sspAssetTransfer;
    }
}
