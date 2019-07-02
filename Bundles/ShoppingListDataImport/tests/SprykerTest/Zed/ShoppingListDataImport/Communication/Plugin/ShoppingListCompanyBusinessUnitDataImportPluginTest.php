<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShoppingListDataImport\Communication\Plugin;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\ShoppingList\Communication\Plugin\ReadShoppingListPermissionPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingListPermissionStoragePlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\WriteShoppingListPermissionPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListCompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListDataImport
 * @group Communication
 * @group Plugin
 * @group ShoppingListCompanyBusinessUnitDataImportPluginTest
 * Add your own group annotations below this line
 * @group ShoppingList
 */
class ShoppingListCompanyBusinessUnitDataImportPluginTest extends Unit
{
    protected const TEST_SHOPPING_LIST_KEY = 'test-shopping-list';
    protected const TEST_COMPANY_BUSINESS_UNIT_KEY = 'test-company-business-unit-key';
    protected const TEST_PRODUCT_CONCRETE_SKU = 'test-product-concrete-sku';

    /**
     * @var \SprykerTest\Zed\ShoppingListDataImport\ShoppingListDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUser;

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

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new ReadShoppingListPermissionPlugin(),
            new WriteShoppingListPermissionPlugin(),
        ]);
        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new ShoppingListPermissionStoragePlugin(),
        ]);
        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();
        $this->tester->getShoppingListFacade()->installShoppingListPermissions();

        $this->tester->ensureShoppingListCompanyBusinessUnitDatabaseTableIsEmpty();
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::KEY => static::TEST_COMPANY_BUSINESS_UNIT_KEY,
        ]);

        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $ownerCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
        ]);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $this->companyUser = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyBusinessUnitTransfer->getFkCompany(),
        ]);

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
        $this->tester->deleteCompanyUser($this->companyUser);
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureShoppingListCompanyBusinessUnitDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(Configuration::dataDir() . 'import/shopping_list_company_business_unit.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $shoppingListCompanyBusinessUnitDataImportPlugin = new ShoppingListCompanyBusinessUnitDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shoppingListCompanyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertShoppingListCompanyBusinessUnitDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenShoppingListNotFoundByKey(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(Configuration::dataDir() . 'import/shopping_list_company_business_unit_shopping_list_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListCompanyBusinessUnitDataImportPlugin = new ShoppingListCompanyBusinessUnitDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find shopping list by key "incorrect-shopping-list-key"');

        // Act
        $shoppingListCompanyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyUserNotFoundByKey(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(Configuration::dataDir() . 'import/shopping_list_company_business_unit_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListCompanyBusinessUnitDataImportPlugin = new ShoppingListCompanyBusinessUnitDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find company business unit by key "incorrect-company-business-unit-key"');

        // Act
        $shoppingListCompanyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenPermissionGroupNotFoundByName(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(Configuration::dataDir() . 'import/shopping_list_company_business_permission_group_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListCompanyBusinessUnitDataImportPlugin = new ShoppingListCompanyBusinessUnitDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find shopping list permission group by name "incorrect-permission-group-name"');

        // Act
        $shoppingListCompanyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $shoppingListCompanyBusinessUnitDataImportPlugin = new ShoppingListCompanyBusinessUnitDataImportPlugin();

        // Assert
        $this->assertSame(ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_COMPANY_BUSINESS_UNIT, $shoppingListCompanyBusinessUnitDataImportPlugin->getImportType());
    }
}
