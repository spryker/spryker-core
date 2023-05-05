<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImport\MerchantProductOfferStoreDataImportPlugin;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantProductOfferStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferStoreDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @var bool
     */
    protected static $isProductOfferDataCreated = false;

    /**
     * @return void
     */
    protected function _before(): void
    {
        if (!static::$isProductOfferDataCreated) {
            $this->tester->truncateProductOffers();
            $this->tester->assertProductOfferDatabaseTableIsEmpty();

            //these values come from the .csv files tested in this data import
            $this->tester->haveProductOffer(['productOfferReference' => 'offer171261249']);
            $this->tester->haveProductOffer(['productOfferReference' => 'offer271261249']);
            $this->tester->haveProductOffer(['productOfferReference' => 'offer371261249']);
            $this->tester->haveProductOffer(['productOfferReference' => 'offer471261249']);
            $this->tester->haveProductOffer(['productOfferReference' => 'offer571261249']);
            $this->tester->haveProductOffer(['productOfferReference' => 'offer671261249']);
            $this->tester->haveProductOffer(['productOfferReference' => 'offer771261249']);
        }
        static::$isProductOfferDataCreated = true;
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->truncateProductOfferStores();
        $this->tester->assertProductOfferStoreDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_product_offer_store.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantProductOfferStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertProductOfferStoreDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = new MerchantProductOfferStoreDataImportPlugin();

        // Assert
        $this->assertSame(MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER_STORE, $dataImportPlugin->getImportType());
    }
}
