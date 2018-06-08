<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductListDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListCategoryDataImportPlugin;
use Spryker\Zed\ProductListDataImport\ProductListDataImportConfig;

/**
 * Auto-generated group annotations
 *
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

        // Act
        $productListCategoryDataImportPlugin = new ProductListCategoryDataImportPlugin();
        $dataImporterReportTransfer = $productListCategoryDataImportPlugin->import(
            $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_category.csv')
        );

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertProductListCategoryTableContainsRecords();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCategoryKeyIsNotDefined(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_category_without_category_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('"category_key" is required.');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListCategoryDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCategoryIsNotFound(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_category_with_invalid_category_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find Category by key "not-existing-category-key"');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListCategoryDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductListKeyIsNotDefined(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_category_without_product_list_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('"product_list_key" is required.');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListCategoryDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductListIsNotFound(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_category_with_invalid_product_list_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find Product List by key "not-existing-product-list-key"');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListCategoryDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
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
