<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShoppingListDataImport\Communication\Plugin;

use Codeception\Test\Unit;
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
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListCompanyUserDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListDataImport
 * @group Communication
 * @group Plugin
 * @group ShoppingListCompanyUserDataImportPluginTest
 * Add your own group annotations below this line
 * @group ShoppingList
 */
class ShoppingListCompanyUserDataImportPluginTest extends Unit
{
    protected const TEST_SHOPPING_LIST_KEY = 'test-shopping-list';
    protected const TEST_COMPANY_USER_KEY = 'test-company-user-key';

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

        $companyTransfer = $this->tester->haveCompany();

        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $ownerCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $this->companyUser = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::KEY => static::TEST_COMPANY_USER_KEY,
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

        $this->tester->ensureShoppingListCompanyUserDatabaseTableIsEmpty();
        $this->tester->deleteShoppingList($this->shoppingListTransfer);
        $this->tester->deleteCompanyUser($this->companyUser);
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureShoppingListCompanyUserDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shopping_list_company_user.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $shoppingListCompanyUserDataImportPlugin = new ShoppingListCompanyUserDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shoppingListCompanyUserDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertShoppingListCompanyUserDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenShoppingListNotFoundByKey(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shopping_list_company_user_shopping_list_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListCompanyUserDataImportPlugin = new ShoppingListCompanyUserDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find shopping list by key "incorrect-shopping-list-key"');

        // Act
        $shoppingListCompanyUserDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyUserNotFoundByKey(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shopping_list_company_user_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListCompanyUserDataImportPlugin = new ShoppingListCompanyUserDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find company user by key "incorrect-company-user-key"');

        // Act
        $shoppingListCompanyUserDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenPermissionGroupNotFoundByName(): void
    {
        $this->tester->ensureShoppingListItemDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shopping_list_company_user_Permission_group_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListCompanyUserDataImportPlugin = new ShoppingListCompanyUserDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find shopping list permission group by name "incorrect-permission-group-name"');

        // Act
        $shoppingListCompanyUserDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $shoppingListCompanyUserDataImportPlugin = new ShoppingListCompanyUserDataImportPlugin();

        // Assert
        $this->assertSame(ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_COMPANY_USER, $shoppingListCompanyUserDataImportPlugin->getImportType());
    }
}
