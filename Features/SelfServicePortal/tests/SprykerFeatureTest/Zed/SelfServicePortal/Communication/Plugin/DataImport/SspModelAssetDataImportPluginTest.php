<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\DataImport\SspModelAssetDataImportPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group SspModelAssetDataImportPluginTest
 */
class SspModelAssetDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH = 'import/ssp_model_asset.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_INVALID = 'import/ssp_model_asset_invalid.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_NONEXISTENT_ASSET = 'import/ssp_model_asset_nonexistent_asset.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_NONEXISTENT_MODEL = 'import/ssp_model_asset_nonexistent_model.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_DUPLICATE = 'import/ssp_model_asset_duplicate.csv';

    protected SspModelTransfer $sspModelTransfer1;

    protected SspModelTransfer $sspModelTransfer2;

    protected SspAssetTransfer $sspAssetTransfer1;

    protected SspAssetTransfer $sspAssetTransfer2;

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->ensureSspAssetToSspModelTableIsEmpty();

        $this->sspModelTransfer1 = $this->tester->haveSspModel([
            'reference' => 'MODEL--1',
            'name' => 'Test Model 1',
            'code' => 'TEST001',
            'imageUrl' => 'https://example.com/image1.jpg',
        ]);

        $this->sspModelTransfer2 = $this->tester->haveSspModel([
            'reference' => 'MODEL--2',
            'name' => 'Test Model 2',
            'code' => 'TEST002',
            'imageUrl' => 'https://example.com/image2.jpg',
        ]);

        $this->sspAssetTransfer1 = $this->tester->haveAsset([
            'reference' => 'ASSET--1',
            'name' => 'Test Asset 1',
            'serialNumber' => 'SN123456789',
            'status' => 'active',
        ]);

        $this->sspAssetTransfer2 = $this->tester->haveAsset([
            'reference' => 'ASSET--2',
            'name' => 'Test Asset 2',
            'serialNumber' => 'SN987654321',
            'status' => 'active',
        ]);
    }

    public function testImportImportsSspModelAssetRelations(): void
    {
        // Arrange
        $sspModelAssetDataImportPlugin = new SspModelAssetDataImportPlugin();
        $sspModelAssetDataImportPlugin = $this->overwriteConfig($sspModelAssetDataImportPlugin);

        // Act
        $dataImporterReportTransfer = $sspModelAssetDataImportPlugin->import(
            (new DataImporterConfigurationTransfer())
                ->setReaderConfiguration(
                    (new DataImporterReaderConfigurationTransfer())->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH),
                ),
        );

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());

        $sspAssetToSspModelRelations1 = $this->tester->getSspModelAssetRelations($this->sspModelTransfer1->getIdSspModel());
        $sspAssetToSspModelRelations2 = $this->tester->getSspModelAssetRelations($this->sspModelTransfer2->getIdSspModel());

        $this->assertCount(1, $sspAssetToSspModelRelations1, 'Expected number of SspAsset-SspModel relations in database after import');
        $this->assertCount(1, $sspAssetToSspModelRelations2, 'Expected number of SspAsset-SspModel relations in database after import');

        $this->tester->isSspModelAssetRelationExists(
            $this->sspModelTransfer1->getIdSspModel(),
            $this->sspAssetTransfer1->getIdSspAsset(),
        );
        $this->tester->isSspModelAssetRelationExists(
            $this->sspModelTransfer2->getIdSspModel(),
            $this->sspAssetTransfer2->getIdSspAsset(),
        );
    }

    public function testImportWithInvalidDataThrowsException(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_INVALID);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);

        // Act
        $sspModelAssetDataImportPlugin = new SspModelAssetDataImportPlugin();
        $sspModelAssetDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testGetImportType(): void
    {
        // Arrange
        $sspModelAssetDataImportPlugin = new SspModelAssetDataImportPlugin();

        // Act
        $importType = $sspModelAssetDataImportPlugin->getImportType();

        // Assert
        $this->assertSame('ssp-model-asset', $importType);
    }

    public function testImportWithNonExistentAssetThrowsException(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_NONEXISTENT_ASSET);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);

        // Act
        $sspModelAssetDataImportPlugin = new SspModelAssetDataImportPlugin();
        $sspModelAssetDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testImportWithNonExistentModelThrowsException(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_NONEXISTENT_MODEL);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);

        // Act
        $sspModelAssetDataImportPlugin = new SspModelAssetDataImportPlugin();
        $sspModelAssetDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testImportWithDuplicateRelationSkipsDuplicate(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_DUPLICATE);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer);

        $sspModelAssetDataImportPlugin = new SspModelAssetDataImportPlugin();
        $sspModelAssetDataImportPlugin = $this->overwriteConfig($sspModelAssetDataImportPlugin);

        // Act
        $dataImporterReportTransfer = $sspModelAssetDataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $sspAssetToSspModelRelations = $this->tester->getSspModelAssetRelations($this->sspModelTransfer1->getIdSspModel());
        $this->assertCount(1, $sspAssetToSspModelRelations, 'Expected only one relation even with duplicate entries');
    }

    protected function overwriteConfig(SspModelAssetDataImportPlugin $sspModelAssetDataImportPlugin): SspModelAssetDataImportPlugin
    {
        $moduleNameConstant = '\Pyz\Zed\SelfServicePortal\SelfServicePortalConfig::MODULE_NAME';

        if (!defined($moduleNameConstant)) {
            return $sspModelAssetDataImportPlugin;
        }

        $configMock = $this->createPartialMock(SelfServicePortalConfig::class, ['getSspModelAssetDataImporterConfiguration']);
        $configMock->method('getSspModelAssetDataImporterConfiguration')
            ->willReturn(
                (new SelfServicePortalConfig())
                    ->getSspModelAssetDataImporterConfiguration()
                    ->setModuleName(
                        constant($moduleNameConstant),
                    ),
            );

        $sspModelAssetDataImportPlugin->setBusinessFactory(
            (new SelfServicePortalBusinessFactory())
                ->setConfig($configMock),
        );

        return $sspModelAssetDataImportPlugin;
    }
}
