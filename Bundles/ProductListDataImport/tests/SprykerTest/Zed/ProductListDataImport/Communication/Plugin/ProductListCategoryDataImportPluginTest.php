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
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListCategoryDataImportPlugin;
use Spryker\Zed\ProductListDataImport\ProductListDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductListDataImport
 * @group Communication
 * @group Plugin
 * @group ProductListCategoryDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductListCategoryDataImportPluginTest extends Unit
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
        $this->tester->ensureProductListCategoryTableIsEmpty();
        $this->tester->haveProductLists();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_list_to_category.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productListCategoryDataImportPlugin = new ProductListCategoryDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $productListCategoryDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertProductListCategoryTableContainsRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Assert
        $productListCategoryDataImportPlugin = new ProductListCategoryDataImportPlugin();
        $this->assertSame(ProductListDataImportConfig::IMPORT_TYPE_PRODUCT_LIST_CATEGORY, $productListCategoryDataImportPlugin->getImportType());
    }
}
