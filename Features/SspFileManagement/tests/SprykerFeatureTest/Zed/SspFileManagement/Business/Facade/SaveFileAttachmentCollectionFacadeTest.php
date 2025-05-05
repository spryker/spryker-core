<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspFileManagement\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SspFileManagement\Persistence\Base\SpyCompanyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpySspAssetFileQuery;
use Spryker\Service\Flysystem\Plugin\FileSystem\FileSystemWriterPlugin;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;
use SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface;
use SprykerFeatureTest\Zed\SspFileManagement\SspFileManagementBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspFileManagement
 * @group Business
 * @group Facade
 * @group SaveFileAttachmentCollectionFacadeTest
 * Add your own group annotations below this line
 */
class SaveFileAttachmentCollectionFacadeTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspFileManagement\SspFileManagementBusinessTester
     */
    protected SspFileManagementBusinessTester $tester;

    /**
     * @var string
     */
    protected const PLUGIN_WRITER = 'PLUGIN_WRITER';

    /**
     * @var string
     */
    public const PLUGIN_COLLECTION_FILESYSTEM_BUILDER = 'filesystem builder plugin collection';

    protected SspFileManagementFacadeInterface|AbstractFacade $facade;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->tester->setDependency(static::PLUGIN_WRITER, new FileSystemWriterPlugin());
        $this->tester->setDependency(static::PLUGIN_COLLECTION_FILESYSTEM_BUILDER, [
            new LocalFilesystemBuilderPlugin(),
        ]);
        $this->tester->ensureFileAttachmentTablesAreEmpty();
        $this->facade = $this->tester->getFacade();
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array<string, mixed> $filesData
     * @param array<string, mixed> $companiesData
     * @param array<string, mixed> $sspAssetsData
     * @param array<string, mixed> $businessUnitsData
     * @param array<string, mixed> $existingFileAttachmentsData
     * @param array<string, mixed> $fileAttachmentsToAdd
     * @param array<string, mixed> $fileAttachmentsToRemove
     * @param int $expectedFileAttachmentEntitiesCount
     *
     * @return void
     */
    public function testSaveFileAttachmentCollection(
        array $filesData,
        array $companiesData,
        array $sspAssetsData,
        array $businessUnitsData,
        array $existingFileAttachmentsData,
        array $fileAttachmentsToAdd,
        array $fileAttachmentsToRemove,
        int $expectedFileAttachmentEntitiesCount
    ): void {
        // Arrange
        $files = [];
        foreach ($filesData as $fileData) {
            $files[$fileData['uuid']] = $this->tester->haveFile([
                FileTransfer::UUID => $fileData['uuid'],
            ]);
        }

        $companies = [];
        foreach ($companiesData as $companyData) {
            $companies[$companyData['uuid']] = $this->tester->haveCompany([
                CompanyTransfer::UUID => $companyData['uuid'],
            ]);
        }

        $sspAssets = [];
        foreach ($sspAssetsData as $sspAssetData) {
            $sspAssets[$sspAssetData['reference']] = $this->tester->haveAsset([
                SspAssetTransfer::REFERENCE => $sspAssetData['reference'],
            ]);
        }

        $businessUnits = [];
        foreach ($businessUnitsData as $businessUnitData) {
            $businessUnits[$businessUnitData['uuid']] = $this->tester->haveCompanyBusinessUnit([
                CompanyBusinessUnitTransfer::UUID => $businessUnitData['uuid'],
                CompanyBusinessUnitTransfer::FK_COMPANY => $companies[$businessUnitData['companyUuid']]->getIdCompany(),
                CompanyBusinessUnitTransfer::COMPANY => $companies[$businessUnitData['companyUuid']],
            ]);
        }

        $fileAttachmentCollectionRequestTransfer = new FileAttachmentCollectionRequestTransfer();
        foreach ($fileAttachmentsToAdd as $fileAttachmentToAdd) {
            if (isset($fileAttachmentToAdd['companyUuid'])) {
                $fileAttachmentCollectionRequestTransfer->addFileAttachmentToAdd((new FileAttachmentTransfer())
                    ->setFile($files[$fileAttachmentToAdd['fileUuid']])
                    ->setEntityId($companies[$fileAttachmentToAdd['companyUuid']]->getIdCompany())
                    ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY));
            }
            if (isset($fileAttachmentToAdd['businessUnitUuid'])) {
                $fileAttachmentCollectionRequestTransfer->addFileAttachmentToAdd((new FileAttachmentTransfer())
                    ->setFile($files[$fileAttachmentToAdd['fileUuid']])
                    ->setEntityId($businessUnits[$fileAttachmentToAdd['businessUnitUuid']]->getIdCompanyBusinessUnit())
                    ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT));
            }
            if (isset($fileAttachmentToAdd['sspAssetReference'])) {
                $fileAttachmentCollectionRequestTransfer->addFileAttachmentToAdd((new FileAttachmentTransfer())
                    ->setFile($files[$fileAttachmentToAdd['fileUuid']])
                    ->setEntityId($sspAssets[$fileAttachmentToAdd['sspAssetReference']]->getIdSspAsset())
                    ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET));
            }
        }

        foreach ($fileAttachmentsToRemove as $fileAttachmentToRemove) {
            if (isset($fileAttachmentToRemove['companyUuid'])) {
                $fileAttachmentCollectionRequestTransfer->addFileAttachmentToRemove((new FileAttachmentTransfer())
                    ->setFile($files[$fileAttachmentToRemove['fileUuid']])
                    ->setEntityId($companies[$fileAttachmentToRemove['companyUuid']]->getIdCompany())
                    ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY));
            }
            if (isset($fileAttachmentToRemove['businessUnitUuid'])) {
                $fileAttachmentCollectionRequestTransfer->addFileAttachmentToRemove((new FileAttachmentTransfer())
                    ->setFile($files[$fileAttachmentToRemove['fileUuid']])
                    ->setEntityId($businessUnits[$fileAttachmentToRemove['businessUnitUuid']]->getIdCompanyBusinessUnit())
                    ->setEntityName(SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT));
            }
        }

        foreach ($existingFileAttachmentsData as $existingFileAttachmentData) {
            if (isset($existingFileAttachmentData['companyUuid'])) {
                $this->tester->haveFileAttachment([
                    FileAttachmentTransfer::FILE => $files[$existingFileAttachmentData['fileUuid']],
                    FileAttachmentTransfer::ENTITY_ID => $companies[$existingFileAttachmentData['companyUuid']]->getIdCompany(),
                    FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY,
                ]);
            }
            if (isset($existingFileAttachmentData['businessUnitUuid'])) {
                $this->tester->haveFileAttachment([
                    FileAttachmentTransfer::FILE => $files[$existingFileAttachmentData['fileUuid']],
                    FileAttachmentTransfer::ENTITY_ID => $businessUnits[$existingFileAttachmentData['businessUnitUuid']]->getIdCompanyBusinessUnit(),
                    FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
                ]);
            }
            if (isset($existingFileAttachmentData['sspAssetReference'])) {
                $this->tester->haveFileAttachment([
                    FileAttachmentTransfer::FILE => $files[$existingFileAttachmentData['fileUuid']],
                    FileAttachmentTransfer::ENTITY_ID => $sspAssets[$existingFileAttachmentData['sspAssetReference']]->getIdSspAsset(),
                    FileAttachmentTransfer::ENTITY_NAME => SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET,
                ]);
            }
        }

        // Act
        $this->facade->saveFileAttachmentCollection($fileAttachmentCollectionRequestTransfer);

        // Assert
        $fileAttachmentEntitiesCount = 0;
        foreach ($files as $file) {
            $fileAttachmentEntitiesCount += SpyCompanyFileQuery::create()->filterByFkFile($file->getIdFile())->count();
            $fileAttachmentEntitiesCount += SpyCompanyBusinessUnitFileQuery::create()->filterByFkFile($file->getIdFile())->count();
            $fileAttachmentEntitiesCount += SpySspAssetFileQuery::create()->filterByFkFile($file->getIdFile())->count();
        }

        $this->assertSame($expectedFileAttachmentEntitiesCount, $fileAttachmentEntitiesCount);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function dataProvider(): array
    {
        return [
            'save file attachment collection with single attachment should succeed' => [
                'filesData' => [
                    ['uuid' => '12345678-1234-5678-1234-567812345678'],
                ],
                'companiesData' => [
                    ['uuid' => '6789abcd-1234-5678-1234-56789abcdef0'],
                ],
                'sspAssetsData' => [],
                'businessUnitsData' => [],
                'existingFileAttachmentsData' => [],
                'fileAttachmentsToAdd' => [
                [
                    'companyUuid' => '6789abcd-1234-5678-1234-56789abcdef0',
                    'fileUuid' => '12345678-1234-5678-1234-567812345678',
                ],

                ],
                'fileAttachmentsToRemove' => [],
                'expectedFileAttachmentEntitiesCount' => 1,
            ],
            'save file attachment collection with multiple attachments should succeed' => [
                'filesData' => [
                    ['uuid' => '12345678-1234-5678-1234-563812345678'],
                ],
                'companiesData' => [
                    ['uuid' => '6789abcd-8590-5678-1234-56789abcdef0'],
                ],
                'sspAssetsData' => [
                    ['reference' => '6789abcd-86-5678-1234-58w56jbcdef0'],
                ],
                'businessUnitsData' => [
                    [
                        'uuid' => '6789abcd-1234-5658-1234-56789abcdef0',
                        'companyUuid' => '6789abcd-8590-5678-1234-56789abcdef0',
                    ],
                ],
                'existingFileAttachmentsData' => [
                    [
                        'companyUuid' => '6789abcd-8590-5678-1234-56789abcdef0',
                        'fileUuid' => '12345678-1234-5678-1234-563812345678',
                    ],
                    [
                        'sspAssetReference' => '6789abcd-86-5678-1234-58w56jbcdef0',
                        'fileUuid' => '12345678-1234-5678-1234-563812345678',
                    ],
                ],
                'fileAttachmentsToAdd' => [
                [
                    'businessUnitUuid' => '6789abcd-1234-5658-1234-56789abcdef0',
                    'fileUuid' => '12345678-1234-5678-1234-563812345678',
                ],
                    [
                        'companyUuid' => '6789abcd-8590-5678-1234-56789abcdef0',
                        'fileUuid' => '12345678-1234-5678-1234-563812345678',
                    ],

                ],
                'fileAttachmentsToRemove' => [],
                'expectedFileAttachmentEntitiesCount' => 3,
            ],
            'save file attachment collection when existing attachments have one less record' => [
                'filesData' => [
                    ['uuid' => '12345678-1234-5678-1794-563812345678'],
                ],
                'companiesData' => [
                    ['uuid' => '6789abcd-8590-5678-1234-56789jbcdef0'],
                    ['uuid' => '6789abcd-e590-5678-1234-56789jbcdef0'],
                    ['uuid' => '6t89abcd-e590-5678-1234-56789jbcdef0'],
                ],
                'sspAssetsData' => [],
                'businessUnitsData' => [],
                'existingFileAttachmentsData' => [
                    [
                        'companyUuid' => '6789abcd-8590-5678-1234-56789jbcdef0',
                        'fileUuid' => '12345678-1234-5678-1794-563812345678',
                    ],
                    [
                        'companyUuid' => '6t89abcd-e590-5678-1234-56789jbcdef0',
                        'fileUuid' => '12345678-1234-5678-1794-563812345678',
                    ],
                ],
                'fileAttachmentsToAdd' => [
                    [
                        'companyUuid' => '6789abcd-8590-5678-1234-56789jbcdef0',
                        'fileUuid' => '12345678-1234-5678-1794-563812345678',
                    ],
                    [
                        'companyUuid' => '6789abcd-e590-5678-1234-56789jbcdef0',
                        'fileUuid' => '12345678-1234-5678-1794-563812345678',
                    ],
                ],
                'fileAttachmentsToRemove' => [],
                'expectedFileAttachmentEntitiesCount' => 3,
            ],
            'delete file attachment' => [
                'filesData' => [
                    ['uuid' => '12345678-ye34-5678-1794-563812345678'],
                    ['uuid' => '12345678-pger-5678-1794-563812345678'],
                ],
                'companiesData' => [
                    ['uuid' => '6789abcd-3e-5678-1234-56789jbcdef0'],
                    ['uuid' => '6789abcd-3e-5678-7fre-56789jbcdef0'],
                ],
                'sspAssetsData' => [],
                'businessUnitsData' => [
                    [
                        'companyUuid' => '6789abcd-3e-5678-7fre-56789jbcdef0',
                        'uuid' => '6789abcd-8590-5678-1234-5og89jbcdef0',
                    ],
                ],
                'existingFileAttachmentsData' => [
                    [
                        'companyUuid' => '6789abcd-3e-5678-1234-56789jbcdef0',
                        'fileUuid' => '12345678-ye34-5678-1794-563812345678',
                    ],
                    [
                        'companyUuid' => '6789abcd-3e-5678-7fre-56789jbcdef0',
                        'fileUuid' => '12345678-ye34-5678-1794-563812345678',
                    ],
                    [
                        'businessUnitUuid' => '6789abcd-8590-5678-1234-5og89jbcdef0',
                        'fileUuid' => '12345678-ye34-5678-1794-563812345678',
                    ],
                    [
                        'businessUnitUuid' => '6789abcd-8590-5678-1234-5og89jbcdef0',
                        'fileUuid' => '12345678-pger-5678-1794-563812345678',
                    ],
                ],
                'fileAttachmentsToAdd' => [],
                'fileAttachmentsToRemove' => [
                    [
                        'companyUuid' => '6789abcd-3e-5678-7fre-56789jbcdef0',
                        'fileUuid' => '12345678-ye34-5678-1794-563812345678',
                    ],
                ],
                'expectedFileAttachmentEntitiesCount' => 3,
            ],
            ];
    }
}
