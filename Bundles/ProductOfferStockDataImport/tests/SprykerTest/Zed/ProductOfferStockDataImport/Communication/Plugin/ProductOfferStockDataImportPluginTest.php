<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferStockDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Spryker\Zed\ProductOfferStockDataImport\Communication\Plugin\ProductOfferStockDataImportPlugin;
use Spryker\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferStockDataImport
 * @group Communication
 * @group Plugin
 * @group ProductOfferStockDataImportPluginTest
 * Add your own group annotations below this line
 * @group ProductOfferStock
 */
class ProductOfferStockDataImportPluginTest extends Unit
{
    use DataCleanupHelperTrait;

    protected const PRODUCT_OFFER_REFERENCE_VALUE = 'offer-1';
    protected const STOCK_NAME_VALUE = 'stock-name-1';

    /**
     * @var \SprykerTest\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferStockTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getDataCleanupHelper()->_addCleanup(function () {
            $this->tester->ensureProductOfferStockTableIsEmpty();
        });
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->haveStock([
            TypeTransfer::NAME => static::STOCK_NAME_VALUE,
        ]);

        $merchantTransfer = $this->tester->haveMerchant();

        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_VALUE,
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/product_offer_stock.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $productOfferStockDataImportPlugin = new ProductOfferStockDataImportPlugin();
        $dataImporterReportTransfer = $productOfferStockDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertProductOfferStockDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $productOfferStockDataImportPlugin = new ProductOfferStockDataImportPlugin();

        // Assert
        $this->assertSame(ProductOfferStockDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_STOCK, $productOfferStockDataImportPlugin->getImportType());
    }
}
