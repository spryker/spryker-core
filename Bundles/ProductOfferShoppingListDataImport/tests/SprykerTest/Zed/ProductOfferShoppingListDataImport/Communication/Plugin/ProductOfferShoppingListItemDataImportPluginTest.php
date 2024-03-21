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
use Generated\Shared\Transfer\ShoppingListItemTransfer;
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
     * @var string This is from the _data/import/product-offer-shopping-list-item.csv file
     */
    protected const TEST_SHOPPING_LIST_ITEM_KEY = 'test-shopping-list-879512';

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
        $companyTransfer = $this->tester->haveCompany();
        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            'customer' => $customerTransfer,
            'fkCompany' => $companyTransfer->getIdCompany(),
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer(['productOfferReference' => 'test-offer-127386']);
        $shoppingListTransfer = $this->tester->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
        ]);
        $this->tester->haveShoppingListItem([
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $shoppingListTransfer->getIdShoppingList(),
            ShoppingListItemTransfer::ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            ShoppingListItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
            ShoppingListItemTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            ShoppingListItemTransfer::KEY => static::TEST_SHOPPING_LIST_ITEM_KEY,
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
