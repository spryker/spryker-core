<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantStockDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\MerchantStockDataImport\Communication\Plugin\MerchantStockDataImportPlugin;
use Spryker\Zed\MerchantStockDataImport\MerchantStockDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantStockDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group MerchantStockDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantStockDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantStockDataImport\MerchantStockDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->createMerchantStockRelatedData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_stock.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $merchantStockDataImportPlugin = new MerchantStockDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $merchantStockDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $merchantStockDataImportPlugin = new MerchantStockDataImportPlugin();
        $this->assertSame(MerchantStockDataImportConfig::IMPORT_TYPE_MERCHANT_STOCK, $merchantStockDataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function createMerchantStockRelatedData(): void
    {
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => 'merchant-test-reference-1']);
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => 'merchant-test-reference-2']);
        $this->tester->haveStock([StockTransfer::NAME => 'Warehouse 1']);
        $this->tester->haveStock([StockTransfer::NAME => 'Warehouse 2']);
    }
}
