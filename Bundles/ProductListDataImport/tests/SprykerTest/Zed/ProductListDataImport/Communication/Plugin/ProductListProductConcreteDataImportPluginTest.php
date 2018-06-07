<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductListDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListProductConcreteDataImportPlugin;
use Spryker\Zed\ProductListDataImport\ProductListDataImportConfig;

/**
 * Auto-generated group annotations
 *
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

        // Act
        $productListProductConcreteDataImportPlugin = new ProductListProductConcreteDataImportPlugin();
        $dataImporterReportTransfer = $productListProductConcreteDataImportPlugin->import(
            $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_concrete_product.csv')
        );

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertProductListConcreteProductTableContainsRecords();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductListKeyIsNotDefined(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_concrete_product_without_product_list_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('"product_list_key" is required.');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListProductConcreteDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductListIsNotFound(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_concrete_product_with_invalid_product_list_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find Product List by key "not-existing-product-list-key"');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListProductConcreteDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductConcreteSkuIsNotDefined(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_concrete_product_without_concrete_sku.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('"concrete_sku" is required.');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListProductConcreteDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductConcreteIsNotFound(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->tester->getDataImporterReaderConfigurationTransfer('import/product_list_to_concrete_product_with_invalid_concrete_sku.csv');
        $dataImportConfigurationTransfer->setThrowException(true);
        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find Product Concrete by sku "invalid-concrete-sku"');
        //Act
        $merchantRelationshipProductListDataImportPlugin = new ProductListProductConcreteDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
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
