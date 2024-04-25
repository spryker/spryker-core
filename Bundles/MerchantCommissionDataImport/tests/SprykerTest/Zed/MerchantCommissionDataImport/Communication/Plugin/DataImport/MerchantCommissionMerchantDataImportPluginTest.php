<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionMerchantDataImportPlugin;
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
 * @group MerchantCommissionMerchantDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantCommissionMerchantDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_KEY = 'test-mc-1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_1 = 'test-mr-1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_2 = 'test-mr-2';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportConfig::IMPORT_TYPE_MERCHANT_COMMISSION_MERCHANT
     *
     * @var string
     */
    protected const IMPORT_TYPE_MERCHANT_COMMISSION_MERCHANT = 'merchant-commission-merchant';

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
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::KEY => static::TEST_MERCHANT_COMMISSION_KEY,
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $this->tester->haveMerchantCommissionGroup(),
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission-merchant/merchant_commission_merchant_valid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionMerchantDataImportPlugin = new MerchantCommissionMerchantDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionMerchantDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(0, $dataImporterReportTransfer->getMessages());
        $this->assertCount(1, $this->tester->getMerchantCommissionMerchantQuery());
    }

    /**
     * @return void
     */
    public function testReturnsErrorsWhenRequiredDataIsMissing(): void
    {
        // Arrange
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::KEY => static::TEST_MERCHANT_COMMISSION_KEY,
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $this->tester->haveMerchantCommissionGroup(),
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission-merchant/merchant_commission_merchant_invalid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionMerchantDataImportPlugin = new MerchantCommissionMerchantDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionMerchantDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(2, $dataImporterReportTransfer->getMessages());
        $this->assertCount(0, $this->tester->getMerchantCommissionMerchantQuery());

        $dataImporterMessagesIterator = $dataImporterReportTransfer->getMessages()->getIterator();
        $this->assertStringContainsString(
            'Could not find Merchant Commission by the key "test-mc-invalid"',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            'Could not find Merchant by the reference "test-mr-invalid"',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsCorrectImporterType(): void
    {
        // Arrange
        $merchantCommissionMerchantDataImportPlugin = new MerchantCommissionMerchantDataImportPlugin();

        // Act
        $importType = $merchantCommissionMerchantDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_MERCHANT_COMMISSION_MERCHANT, $importType);
    }
}
