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
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\DataImport\SspModelDataImportPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group SspModelDataImportPluginTest
 */
class SspModelDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 3;

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH = 'import/ssp_model.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_INVALID = 'import/ssp_model_invalid.csv';

    /**
     * @var array<string, array<string, string>>
     */
    protected const SSP_MODEL_DATA = [
        'MODEL--1' => [
            'name' => 'Test Model 1',
            'code' => 'TEST001',
            'imageUrl' => 'https://example.com/image1.jpg',
        ],
        'MODEL--2' => [
            'name' => 'Test Model 2',
            'code' => 'TEST002',
            'imageUrl' => 'https://example.com/image2.jpg',
        ],
        'MODEL--3' => [
            'name' => 'Test Model 3',
            'code' => 'TEST003',
            'imageUrl' => '',
        ],
    ];

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->clearSspModelData();
    }

    public function testImportImportsSspModels(): void
    {
        // Arrange
        $sspModelDataImportPlugin = new SspModelDataImportPlugin();
        $sspModelDataImportPlugin = $this->overwriteConfig($sspModelDataImportPlugin);

        // Act
        $dataImporterReportTransfer = $sspModelDataImportPlugin->import(
            (new DataImporterConfigurationTransfer())
                ->setReaderConfiguration(
                    (new DataImporterReaderConfigurationTransfer())
                    ->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH),
                ),
        );

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());

        $sspModelTransfers = $this->tester->getAllSspModels();
        $this->assertCount(static::EXPECTED_IMPORT_COUNT, $sspModelTransfers, 'Expected number of SspModels in database after import');

        foreach ($sspModelTransfers as $sspModelTransfer) {
            $this->assertArrayHasKey($sspModelTransfer->getReference(), static::SSP_MODEL_DATA);
            $sspModelData = static::SSP_MODEL_DATA[$sspModelTransfer->getReference()];
            $this->assertSame($sspModelData['name'], $sspModelTransfer->getName());
            $this->assertSame($sspModelData['code'], $sspModelTransfer->getCode());
            $this->assertSame($sspModelData['imageUrl'], $sspModelTransfer->getImageUrl());
        }
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
        (new SspModelDataImportPlugin())->import($dataImporterConfigurationTransfer);
    }

    public function testGetImportType(): void
    {
        // Arrange
        $sspModelDataImportPlugin = new SspModelDataImportPlugin();

        // Act
        $importType = $sspModelDataImportPlugin->getImportType();

        // Assert
        $this->assertSame('ssp-model', $importType);
    }

    protected function overwriteConfig(SspModelDataImportPlugin $sspModelDataImportPlugin): SspModelDataImportPlugin
    {
        $moduleNameConstant = '\Pyz\Zed\SelfServicePortal\SelfServicePortalConfig::MODULE_NAME';

        if (!defined($moduleNameConstant)) {
            return $sspModelDataImportPlugin;
        }

        $configMock = $this->createPartialMock(SelfServicePortalConfig::class, ['getSspModelDataImporterConfiguration']);
        $configMock->method('getSspModelDataImporterConfiguration')
            ->willReturn(
                (new SelfServicePortalConfig())
                    ->getSspModelDataImporterConfiguration()
                    ->setModuleName(
                        constant($moduleNameConstant),
                    ),
            );

        $sspModelDataImportPlugin->setBusinessFactory(
            (new SelfServicePortalBusinessFactory())
                ->setConfig($configMock),
        );

        return $sspModelDataImportPlugin;
    }
}
