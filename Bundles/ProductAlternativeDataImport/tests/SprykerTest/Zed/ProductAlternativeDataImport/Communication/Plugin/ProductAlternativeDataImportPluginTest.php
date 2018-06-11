<?php

namespace SprykerTest\Zed\ProductAlternativeDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ProductAlternativeDataImport\Communication\Plugin\ProductAlternativeDataImportPlugin;
use Spryker\Zed\ProductAlternativeDataImport\ProductAlternativeDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductAlternativeDataImport
 * @group Communication
 * @group Plugin
 * @group ProductAlternativeDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductAlternativeDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductAlternativeDataImport\ProductAlternativeDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_alternative.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productAlternativeDataImportPlugin = new ProductAlternativeDataImportPlugin();
        $dataImporterReportTransfer = $productAlternativeDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productAlternativeDataImportPlugin = new ProductAlternativeDataImportPlugin();
        $this->assertSame(ProductAlternativeDataImportConfig::IMPORT_TYPE_PRODUCT_ALTERNATIVE, $productAlternativeDataImportPlugin->getImportType());
    }
}
