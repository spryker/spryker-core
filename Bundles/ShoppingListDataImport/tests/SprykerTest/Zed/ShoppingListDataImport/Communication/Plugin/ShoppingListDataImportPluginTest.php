<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShoppingListDataImport\Communication\Plugin;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListDataImport
 * @group Communication
 * @group Plugin
 * @group ShoppingListDataImportPluginTest
 * Add your own group annotations below this line
 * @group ShoppingList
 */
class ShoppingListDataImportPluginTest extends Unit
{
    protected const TEST_CUSTOMER_REFERENCE = 'test-shopping-list-customer-reference';

    /**
     * @var \SprykerTest\Zed\ShoppingListDataImport\ShoppingListDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureShoppingListDatabaseTableIsEmpty();
        $this->tester->createCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
        ]);

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(Configuration::dataDir() . 'import/shopping_list.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $shoppingListDataImportPlugin = new ShoppingListDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shoppingListDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertShoppingListDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCustomerNotFound(): void
    {
        $this->tester->ensureShoppingListDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(Configuration::dataDir() . 'import/shopping_list_customer_not_exists.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $shoppingListDataImportPlugin = new ShoppingListDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find customer by reference "incorrect-customer-reference"');

        // Act
        $shoppingListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $shoppingListDataImportPlugin = new ShoppingListDataImportPlugin();

        // Assert
        $this->assertSame(ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST, $shoppingListDataImportPlugin->getImportType());
    }
}
