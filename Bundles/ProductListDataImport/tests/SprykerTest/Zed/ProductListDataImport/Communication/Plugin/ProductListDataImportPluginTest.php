<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductListDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListDataImportPlugin;
use Spryker\Zed\ProductListDataImport\ProductListDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductListDataImport
 * @group Communication
 * @group Plugin
 * @group ProductListDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductListDataImportPluginTest extends Unit
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

        // Act
        $productListDataImportPlugin = new ProductListDataImportPlugin();
        $dataImporterReportTransfer = $productListDataImportPlugin->import(
            $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list.csv')
        );

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertProductListTableContainsRecords();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductListKeyIsNotDefined(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_without_product_list_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('"product_list_key" is required.');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Assert
        $productListDataImportPlugin = new ProductListDataImportPlugin();
        $this->assertSame(ProductListDataImportConfig::IMPORT_TYPE_PRODUCT_LIST, $productListDataImportPlugin->getImportType());
    }
}
