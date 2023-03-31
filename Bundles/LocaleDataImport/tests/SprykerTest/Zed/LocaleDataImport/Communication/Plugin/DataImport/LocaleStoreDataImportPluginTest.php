<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\LocaleDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\LocaleDataImport\Communication\Plugin\DataImport\LocaleStoreDataImportPlugin;
use Spryker\Zed\LocaleDataImport\LocaleDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group LocaleDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group LocaleStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class LocaleStoreDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const STORE_NAME_US = 'US';

    /**
     * @var string
     */
    protected const LOCALE_NAME_US = 'en_US';

    /**
     * @var string
     */
    protected const LOCALE_NAME_DE = 'de_DE';

    /**
     * @uses \Spryker\Zed\LocaleDataImport\LocaleDataImportConfig::IMPORT_TYPE_LOCALE_STORE
     *
     * @var string
     */
    protected const IMPORT_TYPE_LOCALE_STORE = 'locale-store';

    /**
     * @var \SprykerTest\Zed\LocaleDataImport\LocaleDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportWillImportLocaleStoreRelationshipsDataSuccessful(): void
    {
        // Arrange
        $this->tester->ensureStoreLocaleDatabaseTableIsEmpty();

        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $locationDeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_DE]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/locale_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $localeStoreDataImportPlugin = new LocaleStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $localeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->assertSame(
            2,
            $this->tester->countLocaleStoreRelations($locationDeTransfer->getIdLocaleOrFail()),
            'Number of store does not match expected value.',
        );
    }

    /**
     * @return void
     */
    public function testImportWillImportLocaleStoreRelationshipsDataWithUnknownLocaleName(): void
    {
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Locale not found: unknown_locale');

        // Arrange
        $this->tester->ensureStoreLocaleDatabaseTableIsEmpty();

        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/locale_store_with_unknown_locale_name.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $localeStoreDataImportPlugin = new LocaleStoreDataImportPlugin();

        // Act
        $localeStoreDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportWillImportLocaleStoreRelationshipsDataWithUnknownStoreName(): void
    {
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Store not found: unknown_store');

        // Arrange
        $this->tester->ensureStoreLocaleDatabaseTableIsEmpty();

        $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_DE]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/locale_store_with_unknown_store_name.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $localeStoreDataImportPlugin = new LocaleStoreDataImportPlugin();

        // Act
        $localeStoreDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedTypeOfImporter(): void
    {
        //Assign
        $priceProductScheduleDataImportPlugin = new LocaleStoreDataImportPlugin();

        //Act
        $importType = $priceProductScheduleDataImportPlugin->getImportType();

        //Assert
        $this->assertSame(LocaleDataImportConfig::IMPORT_TYPE_LOCALE_STORE, $importType);
    }
}
