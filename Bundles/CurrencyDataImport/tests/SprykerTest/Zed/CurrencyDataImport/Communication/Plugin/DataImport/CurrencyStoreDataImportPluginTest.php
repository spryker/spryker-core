<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CurrencyDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\CurrencyDataImport\Communication\Plugin\DataImport\CurrencyStoreDataImportPlugin;
use Spryker\Zed\CurrencyDataImport\CurrencyDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CurrencyDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group CurrencyStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class CurrencyStoreDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_NAME_EUR = 'EUR';

    /**
     * @var \SprykerTest\Zed\CurrencyDataImport\CurrencyDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCurrencyStoreImportImportsData(): void
    {
        // Arrange
        $this->tester->ensureCurrencyStoreDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/currency_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $currencyStoreDataImportPlugin = new CurrencyStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $currencyStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertGreaterThan(0, $this->tester->getCurrencyStoreRelationsCount());
    }

    /**
     * @return void
     */
    public function testImportWithUnknownCurrencyCode(): void
    {
        //Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Currency not found: YYY');

        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/currency_store_with_unknown_currency_name.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $currencyStoreDataImportPlugin = new CurrencyStoreDataImportPlugin();

        // Act
        $currencyStoreDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportWithUnknownStore(): void
    {
        //Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Store not found: YY');

        // Arrange
        $this->tester->haveCurrency([CurrencyTransfer::NAME => static::CURRENCY_NAME_EUR]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/currency_store_with_unknown_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $currencyStoreDataImportPlugin = new CurrencyStoreDataImportPlugin();

        // Act
        $currencyStoreDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testCurrencyStoreGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $currencyDataImportPlugin = new CurrencyStoreDataImportPlugin();

        // Act
        $importType = $currencyDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(CurrencyDataImportConfig::IMPORT_TYPE_CURRENCY_STORE, $importType);
    }
}
