<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PriceProductOfferDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Spryker\Zed\PriceProductOfferDataImport\Communication\Plugin\PriceProductOfferDataImportPlugin;
use Spryker\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductOfferDataImport
 * @group Communication
 * @group Plugin
 * @group PriceProductOfferDataImportPluginTest
 *
 * Add your own group annotations below this line
 */
class PriceProductOfferDataImportPluginTest extends Unit
{
    protected const PRODUCT_OFFER_REFERENCE = 'offer-1';

    /**
     * @var \SprykerTest\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPriceProductOfferDataImportFacade(): void
    {
        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/price_product_offer.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new PriceProductOfferDataImportPlugin();

        // Act
        $dataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($this->hasPriceProductOffers());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = new PriceProductOfferDataImportPlugin();

        // Act
        $importType = $dataImportPlugin->getImportType();

        // Assert
        $this->assertSame(PriceProductOfferDataImportConfig::IMPORT_TYPE_PRICE_PRODUCT_OFFER, $importType);
    }

    /**
     * @return bool
     */
    protected function hasPriceProductOffers(): bool
    {
        return SpyPriceProductOfferQuery::create()->exists();
    }
}
