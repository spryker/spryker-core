<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
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

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer('import/product_alternative.csv');
        $dataImportConfigurationTransfer->setThrowException(false);

        $productAlternativeDataImportPlugin = new ProductAlternativeDataImportPlugin();
        $dataImporterReportTransfer = $productAlternativeDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer('import/product_alternative_with_invalid_sku.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $productAlternativeDataImportPlugin = new ProductAlternativeDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Product concrete with "999999999" SKU was not found during data import');
        $productAlternativeDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productAlternativeDataImportPlugin = new ProductAlternativeDataImportPlugin();
        $this->assertSame(ProductAlternativeDataImportConfig::IMPORT_TYPE_PRODUCT_ALTERNATIVE, $productAlternativeDataImportPlugin->getImportType());
    }

    /**
     * @param string $file
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function getDataImportConfigurationTransfer(string $file): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $file);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        return $dataImportConfigurationTransfer;
    }
}
