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
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\DataImport\SspAssetDataImportPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group SspAssetDataImportPluginTest
 */
class SspAssetDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH = 'import/ssp_asset.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_INVALID = 'import/ssp_asset_invalid.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_INVALID_URL = 'import/ssp_asset_invalid_url.csv';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureSspAssetRelatedTablesAreEmpty();
    }

    public function testImportImportsSspAssets(): void
    {
        // Arrange
        $this->tester->haveCompanyBusinessUnitForDataImport(['key' => 'test_business_unit_1']);
        $this->tester->haveCompanyBusinessUnitForDataImport(['key' => 'test_business_unit_2']);
        $this->tester->haveCompanyBusinessUnitForDataImport(['key' => 'test_business_unit_3']);

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer);

        $sspAssetDataImportPlugin = new SspAssetDataImportPlugin();
        $sspAssetDataImportPlugin = $this->overwriteConfig($sspAssetDataImportPlugin);

        // Act
        $dataImporterReportTransfer = $sspAssetDataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());

        $assets = $this->tester->getAllSspAssets();
        $this->assertCount(static::EXPECTED_IMPORT_COUNT, $assets, 'Expected number of SSP assets in database after import');

        $assetReferences = array_map(function ($asset) {
            return $asset->getReference();
        }, $assets);

        $this->assertContains('TEST-ASSET-001', $assetReferences, 'Expected "TEST-ASSET-001" asset in database after import');
        $this->assertContains('TEST-ASSET-002', $assetReferences, 'Expected "TEST-ASSET-002" asset in database after import');

        $assetWithUrl = $this->tester->findSspAssetByReference('TEST-ASSET-001');
        $this->assertNotNull($assetWithUrl->getExternalImageUrl(), 'Expected valid external image URL to be stored');

        $assetWithoutUrl = $this->tester->findSspAssetByReference('TEST-ASSET-002');
        $this->assertNull($assetWithoutUrl->getExternalImageUrl(), 'Expected empty external image URL to be stored as null');

        $assignments = $this->tester->getAllSspAssetToCompanyBusinessUnitAssignments();
        $this->assertGreaterThan(0, count($assignments), 'Expected business unit assignments to be created');
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
        $sspAssetDataImportPlugin = new SspAssetDataImportPlugin();
        $sspAssetDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testImportWithInvalidUrlThrowsException(): void
    {
        // Arrange
        $this->tester->haveCompanyBusinessUnitForDataImport(['key' => 'test_business_unit_1', 'name' => 'Test Business Unit 1']);

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_INVALID_URL);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessageMatches('/Invalid external image URL: "invalid-url-format"/');

        // Act
        $sspAssetDataImportPlugin = new SspAssetDataImportPlugin();
        $sspAssetDataImportPlugin = $this->overwriteConfig($sspAssetDataImportPlugin);
        $sspAssetDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testGetImportType(): void
    {
        // Arrange
        $sspAssetDataImportPlugin = new SspAssetDataImportPlugin();

        // Act
        $importType = $sspAssetDataImportPlugin->getImportType();

        // Assert
        $this->assertSame('ssp-asset', $importType);
    }

    protected function overwriteConfig(SspAssetDataImportPlugin $sspAssetDataImportPlugin): SspAssetDataImportPlugin
    {
        $moduleNameConstant = '\Pyz\Zed\SelfServicePortal\SelfServicePortalConfig::MODULE_NAME';

        if (!defined($moduleNameConstant)) {
            return $sspAssetDataImportPlugin;
        }

        $configMock = $this->createPartialMock(SelfServicePortalConfig::class, ['getSspAssetDataImporterConfiguration']);
        $configMock->method('getSspAssetDataImporterConfiguration')
            ->willReturn(
                (new SelfServicePortalConfig())
                    ->getSspAssetDataImporterConfiguration()
                    ->setModuleName(
                        constant($moduleNameConstant),
                    ),
            );

        $sspAssetDataImportPlugin->setBusinessFactory(
            (new SelfServicePortalBusinessFactory())
                ->setConfig($configMock),
        );

        return $sspAssetDataImportPlugin;
    }

    protected function _after(): void
    {
        parent::_after();

        $this->tester->ensureSspAssetRelatedTablesAreEmpty();
    }
}
