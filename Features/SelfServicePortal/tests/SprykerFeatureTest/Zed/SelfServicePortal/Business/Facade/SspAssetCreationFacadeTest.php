<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;
use Spryker\Zed\FileManager\FileManagerDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\SspDashboardManagement\SspAssetDashboardDataExpanderPlugin;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group SspAssetManagementFacadeTest
 */
class SspAssetCreationFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_CURRENT = 'LOCALE_CURRENT';

    /**
     * @var string
     */
    public const PLUGINS_PERMISSION = 'PLUGINS_PERMISSION';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade
     */
    protected $selfServicePortalFacade;

    /**
     * @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected CompanyBusinessUnitTransfer $companyBusinessUnit;

    protected function _before(): void
    {
        $this->selfServicePortalFacade = new SelfServicePortalFacade();

        $this->companyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::KEY => 'Test Company Business Unit',
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $serviceFileSystemMock = $this->createMock(FileManagerToFileSystemServiceInterface::class);
        $serviceFileSystemMock->method('write')->willReturnCallback(function (): void {
        });
        $this->tester->setDependency(FileManagerDependencyProvider::SERVICE_FILE_SYSTEM, $serviceFileSystemMock);
        $this->tester->setDependency(static::LOCALE_CURRENT, 'en_US');
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());
        $this->tester->setDependency(static::PLUGINS_PERMISSION, [
            new ViewCompanySspAssetPermissionPlugin(),
            new ViewBusinessUnitSspAssetPermissionPlugin(),
        ]);
    }

    /**
     * @dataProvider assetSuccessfulCollectionDataProvider
     *
     * @param array<mixed> $sspAssetData
     * @param int $expectedAssetCount
     * @param string $expectedName
     * @param array<string> $expectedValidationErrors
     *
     * @return void
     */
    public function testCreateSspAssetCollectionIsSuccessful(
        array $sspAssetData,
        int $expectedAssetCount,
        string $expectedName,
        array $expectedValidationErrors
    ): void {
        // Arrange
        $sspAssetTransfer = (new SspAssetTransfer())
            ->setName($sspAssetData['name'])
            ->setSerialNumber($sspAssetData['serialNumber'])
            ->setNote($sspAssetData['note']);

        if (!isset($sspAssetData['companyBusinessUnitNotSet']) || !$sspAssetData['companyBusinessUnitNotSet']) {
            $sspAssetTransfer->setCompanyBusinessUnit($this->companyBusinessUnit);
        }

        // Act
        $sspAssetCollectionResponseTransfer = $this->selfServicePortalFacade->createSspAssetCollection(
            (new SspAssetCollectionRequestTransfer())
                ->addSspAsset($sspAssetTransfer),
        );

        // Assert
        $this->assertInstanceOf(SspAssetCollectionResponseTransfer::class, $sspAssetCollectionResponseTransfer);
        $this->assertCount($expectedAssetCount, $sspAssetCollectionResponseTransfer->getSspAssets());

        if ($expectedAssetCount > 0) {
            $createdAssetTransfer = $sspAssetCollectionResponseTransfer->getSspAssets()->getIterator()->current();
            $this->assertNotNull($createdAssetTransfer->getIdSspAsset());
            $this->assertSame($expectedName, $createdAssetTransfer->getName());
        }

        $this->assertSame(
            $expectedValidationErrors,
            array_map(
                fn ($errorTransfer) => $errorTransfer->getMessage(),
                $sspAssetCollectionResponseTransfer->getErrors()->getArrayCopy(),
            ),
        );
    }

    /**
     * @dataProvider assetUpdateDataProvider
     *
     * @param array<string, mixed> $originalData
     * @param array<string, mixed> $updateData
     * @param array<string, mixed> $expectedData
     *
     * @return void
     */
    public function testUpdateSspAssetCollection(
        array $originalData,
        array $updateData,
        array $expectedData
    ): void {
        // Arrange
        $originalData['generateImage'] = $updateData['generateImage'] ?? false;
        $sspAssetTransfer = $this->tester->haveAsset($originalData);
        $originalImageId = $sspAssetTransfer->getImage() ? $sspAssetTransfer->getImage()->getIdFile() : null;

        $sspAssetTransfer = (new SspAssetTransfer())
            ->setIdSspAsset($sspAssetTransfer->getIdSspAsset())
            ->setName($updateData['name'] ?? $sspAssetTransfer->getName())
            ->setSerialNumber($sspAssetTransfer->getSerialNumber())
            ->setNote($updateData['note'] ?? $sspAssetTransfer->getNote());

        if (isset($updateData['generateImage'])) {
            if ($updateData['generateImage']) {
                $imageData = $this->tester->generateSmallFile();
                $this->tester->attachImageToAsset($sspAssetTransfer, $imageData);
            } else {
                if ($sspAssetTransfer->getImage() && $sspAssetTransfer->getImage()->getIdFile()) {
                    $sspAssetTransfer->getImage()->setToDelete(true);
                }
            }
        }

        // Act
        $updateResponseTransfer = $this->selfServicePortalFacade->updateSspAssetCollection(
            (new SspAssetCollectionRequestTransfer())->addSspAsset($sspAssetTransfer),
        );

        // Assert
        $this->assertInstanceOf(SspAssetCollectionResponseTransfer::class, $updateResponseTransfer);

        if ($expectedData['isSuccessful']) {
            $this->assertEmpty($updateResponseTransfer->getErrors());
            $this->assertCount(1, $updateResponseTransfer->getSspAssets());

            $updatedAssetTransfer = $updateResponseTransfer->getSspAssets()->getIterator()->current();
            $this->assertSame($expectedData['name'], $updatedAssetTransfer->getName());
            $this->assertSame($expectedData['note'], $updatedAssetTransfer->getNote());

            if (isset($expectedData['hasImage']) && $expectedData['hasImage']) {
                $this->assertNotNull($updatedAssetTransfer->getImage());
                $this->assertNotNull($updatedAssetTransfer->getImage()->getIdFile());
            }

            if (isset($expectedData['imageUpdated'])) {
                $this->assertNotSame($originalImageId, $updatedAssetTransfer->getImage()->getIdFile());
            }
        } else {
            $this->assertNotEmpty($updateResponseTransfer->getErrors());

            $sspAssetEntity = SpySspAssetQuery::create()->findOneByIdSspAsset($sspAssetTransfer->getIdSspAsset());

            $this->assertSame($expectedData['name'], $sspAssetEntity->getName());
            $this->assertSame($expectedData['note'], $sspAssetEntity->getNote());
        }
    }

    /**
     * @dataProvider getAssetCollectionDataProvider
     *
     * @param array<mixed> $sspAssets
     * @param string $assignedBusinessUnitsCondition
     * @param array<mixed> $sorting
     * @param bool $fetchSspAssetsPerAssignment
     * @param array<mixed> $expectedAssets
     *
     * @return void
     */
    public function testGetSspAssetCollectionReturnsCorrectAssets(
        array $sspAssets,
        string $assignedBusinessUnitsCondition,
        array $sorting,
        bool $fetchSspAssetsPerAssignment,
        array $expectedAssets
    ): void {
        $permissionTransfer = $this->tester->havePermission(new ViewCompanySspAssetPermissionPlugin());
        $permissionCollectionTransfer = (new PermissionCollectionTransfer())->addPermission($permissionTransfer);
        $companyTransfer = $this->tester->haveCompany();

        // Arrange
        $businessUnitByKey = [];
        foreach ($sspAssets as $sspAssetData) {
            $businessUnitAssignments = [];
            foreach ($sspAssetData['assignedCompanyBusinessUnits'] as $assignedCompanyBusinessUnit) {
                $companyBusinessUnitEntity = SpyCompanyBusinessUnitQuery::create()->findOneByKey($assignedCompanyBusinessUnit['key']);
                if ($companyBusinessUnitEntity) {
                    $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())->fromArray($companyBusinessUnitEntity->toArray(), true);
                } else {
                    $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
                        CompanyBusinessUnitTransfer::KEY => $assignedCompanyBusinessUnit['key'],
                        CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                    ]);
                }
                $businessUnitAssignments[] = [
                    SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
                ];
                $businessUnitByKey[$companyBusinessUnitTransfer->getKey()] = $companyBusinessUnitTransfer;
            }

            $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
                $companyTransfer,
                $permissionCollectionTransfer,
            );

            $this->tester->haveAsset([
                SspAssetTransfer::NAME => $sspAssetData['name'],
                SspAssetTransfer::SERIAL_NUMBER => $sspAssetData['serialNumber'],
                SspAssetTransfer::NOTE => $sspAssetData['note'],
                SspAssetTransfer::COMPANY_BUSINESS_UNIT => $this->companyBusinessUnit,
                SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => $businessUnitAssignments,
            ]);
        }

        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();
        $sspAssetConditionsTransfer->setAssignedBusinessUnitId(
            $businessUnitByKey[$assignedBusinessUnitsCondition]->getIdCompanyBusinessUnit(),
        );

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setSspAssetConditions($sspAssetConditionsTransfer)
            ->setPagination(
                (new PaginationTransfer())
                    ->setPage(1)
                    ->setMaxPerPage(count($expectedAssets)),
            )
            ->setInclude(
                (new SspAssetIncludeTransfer())
                    ->setWithAssignedBusinessUnits(true),
            )->setCompanyUser($companyUserTransfer);

        foreach ($sorting as $field => $direction) {
            $sspAssetCriteriaTransfer->addSort(
                (new SortTransfer())
                    ->setField($field)
                    ->setIsAscending($direction === 'ASC'),
            );
        }

        // Act
        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        // Assert
        $this->assertCount(count($expectedAssets), $sspAssetCollectionTransfer->getSspAssets());

        foreach ($expectedAssets as $key => $expectedAsset) {
            $sspAssetTransfer = $sspAssetCollectionTransfer->getSspAssets()->offsetGet($key);
            $this->assertSame($expectedAsset['serialNumber'], $sspAssetTransfer->getSerialNumber());
            $this->assertCount(count($expectedAsset['assignedCompanyBusinessUnits']), $sspAssetTransfer->getBusinessUnitAssignments()->getIterator());
            foreach ($expectedAsset['assignedCompanyBusinessUnits'] as $index => $expectedCompanyBusinessUnit) {
                $companyBusinessUnitEntity = SpyCompanyBusinessUnitQuery::create()->findOneByIdCompanyBusinessUnit($sspAssetTransfer->getBusinessUnitAssignments()->offsetGet($index)->getCompanyBusinessUnit()->getIdCompanyBusinessUnit());
                $this->assertSame($expectedCompanyBusinessUnit['key'], $companyBusinessUnitEntity->getKey());
            }
        }
    }

    public function testSspAssetDashboardDataExpanderPluginWillAddAssetToCollection(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $businessUnitAssignments = [
            [
                SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
            ],
        ];
        $permissionTransfer = $this->tester->havePermission(new ViewCompanySspAssetPermissionPlugin());
        $permissionCollectionTransfer = (new PermissionCollectionTransfer())->addPermission($permissionTransfer);
        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $companyTransfer,
            $permissionCollectionTransfer,
        );

        $expectedName = 'asset name';
        $expectedSerialNumber = 'asset serial number';
        $expectedNote = 'note of the asset';
        $this->tester->haveAsset([
            SspAssetTransfer::NAME => $expectedName,
            SspAssetTransfer::SERIAL_NUMBER => $expectedSerialNumber,
            SspAssetTransfer::NOTE => $expectedNote,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $this->companyBusinessUnit,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => $businessUnitAssignments,
        ]);

        // Act
        $dashboardResponseTransfer = (new SspAssetDashboardDataExpanderPlugin())->provideDashboardData(
            (new DashboardResponseTransfer()),
            (new DashboardRequestTransfer())->setCompanyUser($companyUserTransfer)->setWithSspAssetCount(10),
        );

        // Assert
        $this->assertCount(1, $dashboardResponseTransfer->getDashboardComponentAssets()->getSspAssetCollection()->getSspAssets());
        $this->assertSame(
            1,
            $dashboardResponseTransfer->getDashboardComponentAssets()->getSspAssetCollection()->getSspAssets()->count(),
        );
        /** @var \Generated\Shared\Transfer\SspAssetTransfer $actualSspAssetTransfer */
        $actualSspAssetTransfer = $dashboardResponseTransfer
            ->getDashboardComponentAssets()
            ->getSspAssetCollection()
            ->getSspAssets()
            ->getIterator()
            ->current();

        $this->assertSame($expectedName, $actualSspAssetTransfer->getName());
        $this->assertSame($expectedSerialNumber, $actualSspAssetTransfer->getSerialNumber());
        $this->assertSame($expectedNote, $actualSspAssetTransfer->getNote());
    }

    /**
     * @return array<mixed>
     */
    protected function assetSuccessfulCollectionDataProvider(): array
    {
        return [
            'success with all fields' => [
                'sspAssetData' => [
                    'name' => 'Test Asset',
                    'serialNumber' => '123-456',
                    'note' => 'Test Note',
                ],
                'expectedAssetCount' => 1,
                'expectedName' => 'Test Asset',
                'expectedValidationErrors' => [],
            ],
            'success with required fields only' => [
                'sspAssetData' => [
                    'name' => 'Test Asset',
                    'serialNumber' => null,
                    'note' => null,
                ],
                'expectedAssetCount' => 1,
                'expectedName' => 'Test Asset',
                'expectedValidationErrors' => [],
            ],
            'failure with missing required fields' => [
                'sspAssetData' => [
                    'name' => null,
                    'serialNumber' => '123-456',
                    'note' => 'Test Note',
                ],
                'expectedAssetCount' => 0,
                'expectedName' => '',
                'expectedValidationErrors' => [
                    'self_service_portal.asset.validation.name.not_set',
                ],
            ],
            'failure with missing company business unit' => [
                'sspAssetData' => [
                    'name' => 'Test Asset',
                    'serialNumber' => '123-456',
                    'note' => 'Test Note',
                    'companyBusinessUnitNotSet' => true,
                ],
                'expectedAssetCount' => 1,
                'expectedName' => 'Test Asset',
                'expectedValidationErrors' => [],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getAssetCollectionDataProvider(): array
    {
        return [
            'get assets sorted by id DESC with assigned business unit Test SspAsset Company Business Unit 2' => [
                'sspAssets' => [
                    [
                        'name' => 'Test Asset 1',
                        'serialNumber' => '123-456',
                        'note' => 'Test Note 1',
                        'assignedCompanyBusinessUnits' => [
                            [
                                'key' => 'Test SspAsset Company Business Unit 2',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Test Asset 2',
                        'serialNumber' => '789-012',
                        'note' => 'Test Note 2',
                        'assignedCompanyBusinessUnits' => [
                            [
                                'key' => 'Test SspAsset Company Business Unit 1',
                            ],
                            [
                                'key' => 'Test SspAsset Company Business Unit 2',
                            ],
                        ],
                    ],
                ],
                'assignedBusinessUnitsCondition' => 'Test SspAsset Company Business Unit 2',
                'sorting' => [
                    'id_ssp_asset' => 'DESC',
                ],
                'fetchSspAssetsPerAssignment' => false,
                'expectedAssets' => [
                    [
                        'name' => 'Test Asset 2',
                        'serialNumber' => '789-012',
                        'note' => 'Test Note 2',
                        'assignedCompanyBusinessUnits' => [
                            [
                                'key' => 'Test SspAsset Company Business Unit 2',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Test Asset 1',
                        'serialNumber' => '123-456',
                        'note' => 'Test Note 1',
                        'assignedCompanyBusinessUnits' => [
                            [
                                'key' => 'Test SspAsset Company Business Unit 2',
                            ],
                        ],
                    ],
                ],
            ],
            'get assets sorted by id ASC with assigned business unit Test SspAsset Company Business Unit 3' => [
                'sspAssets' => [
                    [
                        'name' => 'Test Asset 3',
                        'serialNumber' => '123-456',
                        'note' => 'Test Note 3',
                        'assignedCompanyBusinessUnits' => [
                            [
                                'key' => 'Test SspAsset Company Business Unit 3',
                            ],
                            [
                                'key' => 'Test SspAsset Company Business Unit 4',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Test Asset 4',
                        'serialNumber' => '789-012',
                        'note' => 'Test Note 4',
                        'assignedCompanyBusinessUnits' => [
                            [
                                'key' => 'Test SspAsset Company Business Unit 4',
                            ],
                        ],
                    ],
                ],
                'assignedBusinessUnitsCondition' => 'Test SspAsset Company Business Unit 3',
                'sorting' => [
                    'id_ssp_asset' => 'ASC',
                ],
                'fetchSspAssetsPerAssignment' => false,
                'expectedAssets' => [
                    [
                        'name' => 'Test Asset 3',
                        'serialNumber' => '123-456',
                        'note' => 'Test Note 3',
                        'assignedCompanyBusinessUnits' => [
                            [
                                'key' => 'Test SspAsset Company Business Unit 3',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function assetUpdateDataProvider(): array
    {
        return [
            'update asset name and note' => [
                'originalData' => [
                    'name' => 'Original Asset Name',
                    'serialNumber' => 'SN123',
                    'note' => 'Original note',
                ],
                'updateData' => [
                    'name' => 'Updated Asset Name',
                    'note' => 'Updated note',
                ],
                'expectedData' => [
                    'isSuccessful' => true,
                    'name' => 'Updated Asset Name',
                    'note' => 'Updated note',
                ],
            ],
            'update with invalid data' => [
                'originalData' => [
                    'name' => 'Original Asset Name',
                    'serialNumber' => 'SN123',
                    'note' => 'Original note',
                ],
                'updateData' => [
                    'name' => '',
                ],
                'expectedData' => [
                    'isSuccessful' => false,
                    'name' => 'Original Asset Name',
                    'note' => 'Original note',
                ],
            ],
            'update with image' => [
                'originalData' => [
                    'name' => 'Asset with Image',
                    'serialNumber' => 'SN456',
                    'note' => 'Asset with image note',
                    'generateImage' => true,
                ],
                'updateData' => [
                    'generateImage' => true,
                ],
                'expectedData' => [
                    'isSuccessful' => true,
                    'name' => 'Asset with Image',
                    'note' => 'Asset with image note',
                    'hasImage' => true,
                ],
            ],
            'update with image replace' => [
                'originalData' => [
                    'name' => 'Asset with Image',
                    'serialNumber' => 'SN456',
                    'note' => 'Asset with image note',
                    'generateImage' => true,
                ],
                'updateData' => [
                    'generateImage' => true,
                ],
                'expectedData' => [
                    'isSuccessful' => true,
                    'name' => 'Asset with Image',
                    'note' => 'Asset with image note',
                    'hasImage' => true,
                ],
            ],
            'update with image delete' => [
                'originalData' => [
                    'name' => 'Asset with Image',
                    'serialNumber' => 'SN456',
                    'note' => 'Asset with image note',
                    'generateImage' => true,
                ],
                'updateData' => [
                    'generateImage' => false,
                ],
                'expectedData' => [
                    'isSuccessful' => true,
                    'name' => 'Asset with Image',
                    'note' => 'Asset with image note',
                    'hasImage' => false,
                ],
            ],
        ];
    }
}
