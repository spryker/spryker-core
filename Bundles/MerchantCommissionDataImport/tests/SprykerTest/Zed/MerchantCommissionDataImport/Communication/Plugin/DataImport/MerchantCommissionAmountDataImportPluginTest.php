<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionAmountDataImportPlugin;
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
 * @group MerchantCommissionAmountDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantCommissionAmountDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_KEY = 'test-mc-1';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_CODE = 'TST';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportConfig::IMPORT_TYPE_MERCHANT_COMMISSION_AMOUNT
     *
     * @var string
     */
    protected const IMPORT_TYPE_MERCHANT_COMMISSION_AMOUNT = 'merchant-commission-amount';

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

        $this->tester->ensureMerchantCommissionAmountTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensureMerchantCommissionAmountTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportPersistsDataInDatabase(): void
    {
        // Arrange
        $this->tester->haveCurrency([CurrencyTransfer::CODE => static::TEST_CURRENCY_CODE]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::KEY => static::TEST_MERCHANT_COMMISSION_KEY,
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $this->tester->haveMerchantCommissionGroup(),
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission-amount/merchant_commission_amount_valid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionAmountDataImportPlugin = new MerchantCommissionAmountDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionAmountDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(0, $dataImporterReportTransfer->getMessages());
        $this->assertCount(1, $this->tester->getMerchantCommissionAmountQuery());
    }

    /**
     * @return void
     */
    public function testReturnsErrorsWhenRequiredDataIsMissing(): void
    {
        // Arrange
        $this->tester->haveCurrency([CurrencyTransfer::CODE => static::TEST_CURRENCY_CODE]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::KEY => static::TEST_MERCHANT_COMMISSION_KEY,
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $this->tester->haveMerchantCommissionGroup(),
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission-amount/merchant_commission_amount_invalid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionAmountDataImportPlugin = new MerchantCommissionAmountDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionAmountDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(2, $dataImporterReportTransfer->getMessages());
        $this->assertCount(0, $this->tester->getMerchantCommissionAmountQuery());

        $dataImporterMessagesIterator = $dataImporterReportTransfer->getMessages()->getIterator();
        $this->assertStringContainsString(
            'Could not find Merchant Commission by the key "test-mc-invalid"',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            'Could not find Currency by the code "INVALID"',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsCorrectImporterType(): void
    {
        // Arrange
        $merchantCommissionAmountDataImportPlugin = new MerchantCommissionAmountDataImportPlugin();

        // Act
        $importType = $merchantCommissionAmountDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_MERCHANT_COMMISSION_AMOUNT, $importType);
    }
}
