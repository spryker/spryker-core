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
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionStoreDataImportPlugin;
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
 * @group MerchantCommissionStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantCommissionStoreDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_KEY = 'test-mc-1';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @uses \Spryker\Zed\MerchantCommissionDataImport\MerchantCommissionDataImportConfig::IMPORT_TYPE_MERCHANT_COMMISSION_STORE
     *
     * @var string
     */
    protected const IMPORT_TYPE_MERCHANT_COMMISSION_STORE = 'merchant-commission-store';

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
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::KEY => static::TEST_MERCHANT_COMMISSION_KEY,
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $this->tester->haveMerchantCommissionGroup(),
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission-store/merchant_commission_store_valid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionStoreDataImportPlugin = new MerchantCommissionStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(0, $dataImporterReportTransfer->getMessages());
        $this->assertCount(1, $this->tester->getMerchantCommissionStoreQuery());
    }

    /**
     * @return void
     */
    public function testReturnsErrorsWhenRequiredDataIsMissing(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::KEY => static::TEST_MERCHANT_COMMISSION_KEY,
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $this->tester->haveMerchantCommissionGroup(),
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant-commission-store/merchant_commission_store_invalid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantCommissionStoreDataImportPlugin = new MerchantCommissionStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantCommissionStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(2, $dataImporterReportTransfer->getMessages());
        $this->assertCount(0, $this->tester->getMerchantCommissionStoreQuery());

        $dataImporterMessagesIterator = $dataImporterReportTransfer->getMessages()->getIterator();
        $this->assertStringContainsString(
            'Could not find Merchant Commission by the key "test-mc-invalid"',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            'Could not find Store by the name "INVALID"',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsCorrectImporterType(): void
    {
        // Arrange
        $merchantCommissionStoreDataImportPlugin = new MerchantCommissionStoreDataImportPlugin();

        // Act
        $importType = $merchantCommissionStoreDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_MERCHANT_COMMISSION_STORE, $importType);
    }
}
