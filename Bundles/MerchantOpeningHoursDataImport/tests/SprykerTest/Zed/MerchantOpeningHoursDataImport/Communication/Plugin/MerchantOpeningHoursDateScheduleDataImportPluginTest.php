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
use Spryker\Zed\MerchantOpeningHoursDataImport\Communication\Plugin\MerchantOpeningHoursDateScheduleDataImportPlugin;
use Spryker\Zed\MerchantOpeningHoursDataImport\MerchantOpeningHoursDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOpeningHoursDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantOpeningHoursDateScheduleDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantOpeningHoursDateScheduleDataImportPluginTest extends Unit
{
    public const MERCHANT_KEY = 'merchant-profile-data-import-test-key';

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
        $merchantEntity = $this->tester->findMerchantByKey(static::MERCHANT_KEY);
        if ($merchantEntity === null) {
            $this->tester->haveMerchant([
                'merchant_key' => static::MERCHANT_KEY,
            ]);
        }
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant_open_hours_date_schedule.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $merchantOpeningHoursDateScheduleDataImportPlugin = new MerchantOpeningHoursDateScheduleDataImportPlugin();
        $dataImporterReportTransfer = $merchantOpeningHoursDateScheduleDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertMerchantOpeningHoursDateScheduleDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $merchantOpeningHoursDateScheduleDataImportPlugin = new MerchantOpeningHoursDateScheduleDataImportPlugin();

        // Assert
        $this->assertSame(MerchantOpeningHoursDataImportConfig::IMPORT_TYPE_MERCHANT_OPENING_HOURS_DATE_SCHEDULE, $merchantOpeningHoursDateScheduleDataImportPlugin->getImportType());
    }
}
