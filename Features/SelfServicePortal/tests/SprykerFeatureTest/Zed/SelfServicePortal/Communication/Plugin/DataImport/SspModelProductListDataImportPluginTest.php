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
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\DataImport\SspModelProductListDataImportPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group SspModelProductListDataImportPluginTest
 */
class SspModelProductListDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH = 'import/ssp_model_product_list.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_INVALID = 'import/ssp_model_product_list_invalid.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_NONEXISTENT_MODEL = 'import/ssp_model_product_list_nonexistent_model.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_NONEXISTENT_PRODUCT_LIST = 'import/ssp_model_product_list_nonexistent_product_list.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_DUPLICATE = 'import/ssp_model_product_list_duplicate.csv';

    protected SspModelTransfer $sspModelTransfer1;

    protected SspModelTransfer $sspModelTransfer2;

    protected ProductListTransfer $productListTransfer1;

    protected ProductListTransfer $productListTransfer2;

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->ensureSspModelToProductListTableIsEmpty();

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

        $this->productListTransfer1 = $this->tester->haveProductList([
            'key' => 'pl-ssp-001',
            'title' => 'SSP Test Product List 1',
            'type' => 'whitelist',
        ]);

        $this->productListTransfer2 = $this->tester->haveProductList([
            'key' => 'pl-ssp-002',
            'title' => 'SSP Test Product List 2',
            'type' => 'blacklist',
        ]);
    }

    public function testImportImportsSspModelProductListRelations(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer);

        $sspModelProductListDataImportPlugin = new SspModelProductListDataImportPlugin();
        $sspModelProductListDataImportPlugin = $this->overwriteConfig($sspModelProductListDataImportPlugin);

        // Act
        $dataImporterReportTransfer = $sspModelProductListDataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());

        $sspModelToProductListRelations1 = $this->tester->getSspModelToProductListRelations($this->sspModelTransfer1->getIdSspModel());
        $sspModelToProductListRelations2 = $this->tester->getSspModelToProductListRelations($this->sspModelTransfer2->getIdSspModel());

        $this->assertCount(1, $sspModelToProductListRelations1, 'Expected number of SspModel-ProductList relations in database after import');
        $this->assertCount(1, $sspModelToProductListRelations2, 'Expected number of SspModel-ProductList relations in database after import');

        $this->tester->isSspModelProductListRelationExists(
            $this->sspModelTransfer1->getIdSspModel(),
            $this->productListTransfer1->getIdProductList(),
        );
        $this->tester->isSspModelProductListRelationExists(
            $this->sspModelTransfer2->getIdSspModel(),
            $this->productListTransfer2->getIdProductList(),
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
        $sspModelProductListDataImportPlugin = new SspModelProductListDataImportPlugin();
        $sspModelProductListDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testGetImportType(): void
    {
        // Arrange
        $sspModelProductListDataImportPlugin = new SspModelProductListDataImportPlugin();

        // Act
        $importType = $sspModelProductListDataImportPlugin->getImportType();

        // Assert
        $this->assertSame('ssp-model-product-list', $importType);
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
        $sspModelProductListDataImportPlugin = new SspModelProductListDataImportPlugin();
        $sspModelProductListDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testImportWithNonExistentProductListThrowsException(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_NONEXISTENT_PRODUCT_LIST);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);

        // Act
        $sspModelProductListDataImportPlugin = new SspModelProductListDataImportPlugin();
        $sspModelProductListDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testImportWithDuplicateRelationSkipsDuplicate(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_DUPLICATE);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer);

        $sspModelProductListDataImportPlugin = new SspModelProductListDataImportPlugin();
        $sspModelProductListDataImportPlugin = $this->overwriteConfig($sspModelProductListDataImportPlugin);

        // Act
        $dataImporterReportTransfer = $sspModelProductListDataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $sspModelToProductListRelations = $this->tester->getSspModelToProductListRelations($this->sspModelTransfer1->getIdSspModel());
        $this->assertCount(1, $sspModelToProductListRelations, 'Expected only one relation even with duplicate entries');
    }

    protected function overwriteConfig(SspModelProductListDataImportPlugin $sspModelProductListDataImportPlugin): SspModelProductListDataImportPlugin
    {
        $moduleNameConstant = '\Pyz\Zed\SelfServicePortal\SelfServicePortalConfig::MODULE_NAME';

        if (!defined($moduleNameConstant)) {
            return $sspModelProductListDataImportPlugin;
        }

        $configMock = $this->createPartialMock(SelfServicePortalConfig::class, ['getSspModelProductListDataImporterConfiguration']);
        $configMock->method('getSspModelProductListDataImporterConfiguration')
            ->willReturn(
                (new SelfServicePortalConfig())
                    ->getSspModelProductListDataImporterConfiguration()
                    ->setModuleName(
                        constant($moduleNameConstant),
                    ),
            );

        $sspModelProductListDataImportPlugin->setBusinessFactory(
            (new SelfServicePortalBusinessFactory())
                ->setConfig($configMock),
        );

        return $sspModelProductListDataImportPlugin;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->truncateProductListTable(['pl-ssp-001', 'pl-ssp-002']);
    }
}
