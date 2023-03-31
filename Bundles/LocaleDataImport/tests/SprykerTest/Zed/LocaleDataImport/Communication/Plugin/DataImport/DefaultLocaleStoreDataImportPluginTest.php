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
use Spryker\Zed\LocaleDataImport\Communication\Plugin\DataImport\DefaultLocaleStoreDataImportPlugin;
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
 * @group DefaultLocaleStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class DefaultLocaleStoreDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_US = 'US';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const LOCALE_NAME_US = 'en_US';

    /**
     * @var string
     */
    protected const LOCALE_NAME_DE = 'de_DE';

    /**
     * @var \SprykerTest\Zed\LocaleDataImport\LocaleDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportWillImportDefaultLocaleStoreRelationshipsDataSuccessful(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $locationDeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_DE]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/default_locale_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $defaultLocaleStoreDataImportPlugin = new DefaultLocaleStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $defaultLocaleStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->assertSame(
            $locationDeTransfer->getIdLocaleOrFail(),
            $this->tester->getDefaultLocaleIdByStoreName(static::STORE_NAME_AT),
            'Number of store does not match expected value.',
        );
        $this->assertSame(
            $locationDeTransfer->getIdLocaleOrFail(),
            $this->tester->getDefaultLocaleIdByStoreName(static::STORE_NAME_DE),
            'Number of store does not match expected value.',
        );
    }

    /**
     * @return void
     */
    public function testImportWillImportDefaultLocaleStoreRelationshipsDataWithUnknownLocaleName(): void
    {
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Locale not found: unknown_locale');

        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/default_locale_store_with_unknown_locale_name.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $defaultLocaleStoreDataImportPlugin = new DefaultLocaleStoreDataImportPlugin();

        // Act
        $defaultLocaleStoreDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedTypeOfImporter(): void
    {
        //Assign
        $priceProductScheduleDataImportPlugin = new DefaultLocaleStoreDataImportPlugin();

        //Act
        $importType = $priceProductScheduleDataImportPlugin->getImportType();

        //Assert
        $this->assertSame(LocaleDataImportConfig::IMPORT_TYPE_DEFAULT_LOCALE_STORE, $importType);
    }
}
