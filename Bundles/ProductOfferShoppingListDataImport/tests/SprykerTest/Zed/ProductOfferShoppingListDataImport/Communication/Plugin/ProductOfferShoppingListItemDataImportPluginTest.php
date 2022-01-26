<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferShoppingListDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ProductOfferShoppingListDataImport\Communication\Plugin\DataImport\ProductOfferShoppingListItemDataImportPlugin;
use Spryker\Zed\ProductOfferShoppingListDataImport\ProductOfferShoppingListDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShoppingListDataImport
 * @group Communication
 * @group Plugin
 * @group ProductOfferShoppingListItemDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferShoppingListItemDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SHOPPING_LIST_KEY = 'test-shopping-list';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_CONCRETE_SKU = 'test-product-concrete-sku';

    /**
     * @var \SprykerTest\Zed\ProductOfferShoppingListDataImport\ProductOfferShoppingListDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsShoppingListItem(): void
    {
        // Arrange
        $this->tester->ensureShoppingListProductOfferDatabaseTableIsEmpty();
        $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => 'test_customer',
            ShoppingListTransfer::KEY => static::TEST_SHOPPING_LIST_KEY,
        ]);
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product-offer-shopping-list-item.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productOfferShoppingListItemDataImportPlugin = new ProductOfferShoppingListItemDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $productOfferShoppingListItemDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertShoppingListProductOfferDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productOfferShoppingListItemDataImportPlugin = new ProductOfferShoppingListItemDataImportPlugin();
        $this->assertSame(ProductOfferShoppingListDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_SHOPPING_LIST_ITEM, $productOfferShoppingListItemDataImportPlugin->getImportType());
    }
}
