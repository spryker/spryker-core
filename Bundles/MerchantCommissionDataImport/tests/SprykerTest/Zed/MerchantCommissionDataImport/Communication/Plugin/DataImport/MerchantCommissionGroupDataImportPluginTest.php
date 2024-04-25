<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionGroupDataImportPlugin;
use SprykerTest\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommissionDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group MerchantCommissionGroupDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantCommissionGroupDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_GROUP_KEY = 'test-mcg-1';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportConfig::IMPORT_TYPE_MERCHANT_COMMISSION_GROUP
     *
     * @var string
     */
    protected const IMPORT_TYPE_MERCHANT_COMMISSION_GROUP = 'merchant-commission-group';

    /**
     * @var \SprykerTest\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportCommunicationTester
     */
    protected MerchantCommissionDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantCommissionGroupTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensureMerchantCommissionGroupTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportPersistsDataInDatabase(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission-group/merchant_commission_group_valid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionGroupDataImportPlugin = new MerchantCommissionGroupDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionGroupDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(0, $dataImporterReportTransfer->getMessages());
        $this->assertCount(1, $this->tester->getMerchantCommissionGroupQuery());
    }

    /**
     * @return void
     */
    public function testReturnsErrorsWhenRequiredDataIsMissing(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission-group/merchant_commission_group_invalid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionGroupDataImportPlugin = new MerchantCommissionGroupDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionGroupDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(2, $dataImporterReportTransfer->getMessages());
        $this->assertCount(0, $this->tester->getMerchantCommissionGroupQuery());

        $dataImporterMessagesIterator = $dataImporterReportTransfer->getMessages()->getIterator();
        $this->assertStringContainsString(
            '"key" is required',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            '"name" is required',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsCorrectImporterType(): void
    {
        // Arrange
        $merchantCommissionGroupDataImportPlugin = new MerchantCommissionGroupDataImportPlugin();

        // Act
        $importType = $merchantCommissionGroupDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_MERCHANT_COMMISSION_GROUP, $importType);
    }
}
