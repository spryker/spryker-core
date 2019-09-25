<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MultiCartDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\MultiCartDataImport\Communication\Plugin\MultiCartDataImportPlugin;
use Spryker\Zed\MultiCartDataImport\MultiCartDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiCartDataImport
 * @group Communication
 * @group Plugin
 * @group MultiCartDataImportPluginTest
 * Add your own group annotations below this line
 * @group Quote
 * @group MultiCart
 */
class MultiCartDataImportPluginTest extends Unit
{
    protected const TEST_CUSTOMER_REFERENCE = 'DE-test';
    protected const TEST_CUSTOMER_REFERENCE_STORE = 'DE-test-store';

    /**
     * @var \SprykerTest\Zed\MultiCartDataImport\MultiCartDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureQuoteDatabaseTableIsEmpty();
        $this->tester->createCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
        ]);

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/multi_cart.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $multiCartDataImportPlugin = new MultiCartDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $multiCartDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCustomerNotFound(): void
    {
        $this->tester->ensureQuoteDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/multi_cart_customer_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $multiCartDataImportPlugin = new MultiCartDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find customer by reference "DE-INCORRECT"');

        // Act
        $multiCartDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenStoreNotFound(): void
    {
        $this->tester->ensureQuoteDatabaseTableIsEmpty();
        $this->tester->createCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE_STORE,
        ]);

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/multi_cart_store_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $multiCartDataImportPlugin = new MultiCartDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find store by name "NOT_EXISTING"');

        // Act
        $multiCartDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $multiCartDataImportPlugin = new MultiCartDataImportPlugin();

        // Assert
        $this->assertSame(MultiCartDataImportConfig::IMPORT_TYPE_MULTI_CART, $multiCartDataImportPlugin->getImportType());
    }
}
