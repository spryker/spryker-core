<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupTransfer;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionDataImportPlugin;
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
 * @group MerchantCommissionDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantCommissionDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_GROUP_KEY = 'test-mcg-1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_KEY_1 = 'test-mc-1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_KEY_2 = 'test-mc-2';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportConfig::IMPORT_TYPE_MERCHANT_COMMISSION
     *
     * @var string
     */
    protected const IMPORT_TYPE_MERCHANT_COMMISSION = 'merchant-commission';

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

        $this->tester->ensureMerchantCommissionTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensureMerchantCommissionTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportPersistsDataInDatabase(): void
    {
        // Arrange
        $this->tester->haveMerchantCommissionGroup([
            MerchantCommissionGroupTransfer::KEY => static::TEST_MERCHANT_COMMISSION_GROUP_KEY,
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission/merchant_commission_valid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionDataImportPlugin = new MerchantCommissionDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(0, $dataImporterReportTransfer->getMessages());
        $this->assertCount(2, $this->tester->getMerchantCommissionQuery());
    }

    /**
     * @return void
     */
    public function testReturnsErrorsWhenRequiredDataIsMissing(): void
    {
        // Arrange
        $this->tester->haveMerchantCommissionGroup([
            MerchantCommissionGroupTransfer::KEY => static::TEST_MERCHANT_COMMISSION_GROUP_KEY,
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission/merchant_commission_invalid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionDataImportPlugin = new MerchantCommissionDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(5, $dataImporterReportTransfer->getMessages());
        $this->assertCount(0, $this->tester->getMerchantCommissionQuery());

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
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            '"calculator_type_plugin" is required',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            '"is_active" is required',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            'Could not find Merchant Commission Group by the key "test-mcg-invalid"',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsCorrectImporterType(): void
    {
        // Arrange
        $merchantCommissionDataImportPlugin = new MerchantCommissionDataImportPlugin();

        // Act
        $importType = $merchantCommissionDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_MERCHANT_COMMISSION, $importType);
    }
}
