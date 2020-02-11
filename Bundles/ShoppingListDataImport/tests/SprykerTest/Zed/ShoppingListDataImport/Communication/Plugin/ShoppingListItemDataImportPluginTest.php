<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShoppingListDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListItemDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListDataImport
 * @group Communication
 * @group Plugin
 * @group ShoppingListItemDataImportPluginTest
 * Add your own group annotations below this line
 * @group ShoppingList
 */
class ShoppingListItemDataImportPluginTest extends Unit
{
    protected const TEST_SHOPPING_LIST_KEY = 'test-shopping-list';
    protected const TEST_PRODUCT_CONCRETE_SKU = 'test-product-concrete-sku';

    /**
     * @var \SprykerTest\Zed\ShoppingListDataImport\ShoppingListDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected $shoppingListTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $ownerCustomerTransfer = $this->tester->haveCustomer();

        $this->shoppingListTransfer = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $ownerCustomerTransfer->getCustomerReference(),
            ShoppingListTransfer::KEY => static::TEST_SHOPPING_LIST_KEY,
        ]);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::setUp();

        $this->tester->deleteShoppingList($this->shoppingListTransfer);
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();
        $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => static::TEST_PRODUCT_CONCRETE_SKU,
        ]);

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shopping_list_item.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $shoppingListItemDataImportPlugin = new ShoppingListItemDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shoppingListItemDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertShoppingListItemDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenShoppingListNotFoundByKey(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shopping_list_item_shopping_list_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListItemDataImportPlugin = new ShoppingListItemDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find shopping list by key "incorrect-shopping-list-key"');

        // Act
        $shoppingListItemDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductConcreteNotFoundBySku(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shopping_list_item_product_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListItemDataImportPlugin = new ShoppingListItemDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find product concrete by SKU "incorrect-product-concrete-SKU"');

        // Act
        $shoppingListItemDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $shoppingListItemDataImportPlugin = new ShoppingListItemDataImportPlugin();

        // Assert
        $this->assertSame(ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_ITEM, $shoppingListItemDataImportPlugin->getImportType());
    }
}
