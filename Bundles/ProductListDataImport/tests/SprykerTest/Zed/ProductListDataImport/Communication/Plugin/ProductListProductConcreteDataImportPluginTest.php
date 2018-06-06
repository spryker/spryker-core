<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductListDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListProductConcreteDataImportPlugin;
use Spryker\Zed\ProductListDataImport\ProductListDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductListDataImport
 * @group Communication
 * @group Plugin
 * @group ProductListProductConcreteDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductListProductConcreteDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductListDataImport\ProductListDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Assign
        $this->tester->ensureProductListTableIsEmpty();
        $this->tester->haveProductLists();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_list_to_concrete_product.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productListProductConcreteDataImportPlugin = new ProductListProductConcreteDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $productListProductConcreteDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertProductListConcreteProductTableContainsRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Assert
        $productListProductConcreteDataImportPlugin = new ProductListProductConcreteDataImportPlugin();
        $this->assertSame(ProductListDataImportConfig::IMPORT_TYPE_PRODUCT_LIST_PRODUCT_CONCRETE, $productListProductConcreteDataImportPlugin->getImportType());
    }
}
