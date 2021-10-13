<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantOpeningHoursDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\MerchantOpeningHoursDataImport\Communication\Plugin\MerchantOpeningHoursWeekdayScheduleDataImportPlugin;
use Spryker\Zed\MerchantOpeningHoursDataImport\MerchantOpeningHoursDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOpeningHoursDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantOpeningHoursWeekdayScheduleDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantOpeningHoursWeekdayScheduleDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant-opening-hours-data-import-test-reference';

    /**
     * @var \SprykerTest\Zed\MerchantOpeningHoursDataImport\MerchantOpeningHoursDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantOpeningHoursTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $merchantEntity = $this->tester->findMerchantByReference(static::MERCHANT_REFERENCE);
        if ($merchantEntity === null) {
            $this->tester->haveMerchant([
                'merchant_reference' => static::MERCHANT_REFERENCE,
            ]);
        }

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant_open_hours_week_day_schedule.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $merchantOpeningHoursWeekdayScheduleDataImportPlugin = new MerchantOpeningHoursWeekdayScheduleDataImportPlugin();
        $dataImporterReportTransfer = $merchantOpeningHoursWeekdayScheduleDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertMerchantOpeningHoursWeekdayScheduleDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $merchantOpeningHoursWeekdayScheduleDataImportPlugin = new MerchantOpeningHoursWeekdayScheduleDataImportPlugin();

        // Assert
        $this->assertSame(MerchantOpeningHoursDataImportConfig::IMPORT_TYPE_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE, $merchantOpeningHoursWeekdayScheduleDataImportPlugin->getImportType());
    }
}
