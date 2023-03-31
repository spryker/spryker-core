<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CountryDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\CountryDataImport\Communication\Plugin\DataImport\CountryStoreDataImportPlugin;
use Spryker\Zed\CountryDataImport\CountryDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CountryDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group CountryStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class CountryStoreDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const COUNTRY_CODE_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\CountryDataImport\CountryDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCountryStoreImportImportsData(): void
    {
        // Arrange
        $this->tester->ensureCountryStoreDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/country_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $countryStoreDataImportPlugin = new CountryStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $countryStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertGreaterThan(0, $this->tester->getCountryStoreRelationsCount());
    }

    /**
     * @return void
     */
    public function testImportWithUnknownCountryCode(): void
    {
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Country not found: YY');

        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/country_store_with_unknown_country_code.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $countryStoreDataImportPlugin = new CountryStoreDataImportPlugin();

        // Act
        $countryStoreDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportWithUnknownStore(): void
    {
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Store not found: YY');

        // Arrange
        $this->tester->haveCountry([CountryTransfer::ISO2_CODE => static::COUNTRY_CODE_DE]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/country_store_with_unknown_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $countryStoreDataImportPlugin = new CountryStoreDataImportPlugin();

        // Act
        $countryStoreDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testCountryStoreGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $countryDataImportPlugin = new CountryStoreDataImportPlugin();

        // Act
        $importType = $countryDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(CountryDataImportConfig::IMPORT_TYPE_COUNTRY_STORE, $importType);
    }
}
