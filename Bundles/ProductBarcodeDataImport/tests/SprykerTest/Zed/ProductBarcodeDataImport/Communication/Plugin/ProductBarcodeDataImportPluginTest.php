<?php

namespace SprykerTest\Zed\ProductBarcodeDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ProductBarcodeDataImport\Communication\Plugin\ProductBarcodeDataImportPlugin;
use Spryker\Zed\ProductBarcodeDataImport\ProductBarcodeDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductBarcodeDataImport
 * @group Communication
 * @group Plugin
 * @group ProductBarcodeDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductBarcodeDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBarcodeDataImport\ProductBarcodeDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product-barcode.csv');

        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productBarcodeDataImportPlugin = new ProductBarcodeDataImportPlugin();
        $dataImporterReportTransfer = $productBarcodeDataImportPlugin->import($dataImporterConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productBarcodeDataImportPlugin = new ProductBarcodeDataImportPlugin();
        $this->assertSame(ProductBarcodeDataImportConfig::IMPORT_TYPE_PRODUCT_BARCODE, $productBarcodeDataImportPlugin->getImportType());
    }
}
