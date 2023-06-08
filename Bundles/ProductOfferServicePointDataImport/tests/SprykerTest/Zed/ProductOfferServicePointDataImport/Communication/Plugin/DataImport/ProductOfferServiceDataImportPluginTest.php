<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferServicePointDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Spryker\Zed\ProductOfferServicePointDataImport\Communication\Plugin\DataImport\ProductOfferServiceDataImportPlugin;
use SprykerTest\Zed\ProductOfferServicePointDataImport\ProductOfferServicePointDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePointDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ProductOfferServiceDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferServiceDataImportPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductOfferServicePointDataImport\ProductOfferServicePointDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_SERVICE
     *
     * @var string
     */
    protected const IMPORT_TYPE_PRODUCT_OFFER_SERVICE = 'product-offer-service';

    /**
     * @var \SprykerTest\Zed\ProductOfferServicePointDataImport\ProductOfferServicePointDataImportCommunicationTester
     */
    protected ProductOfferServicePointDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferServiceTableAndRelationsAreEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => 'por1',
        ]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => 'por2',
        ]);
        $this->tester->haveService([
            ServiceTransfer::KEY => 's1',
        ]);
        $this->tester->haveService([
            ServiceTransfer::KEY => 's2',
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/product_offer_service.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $productOfferServiceDataImportPlugin = new ProductOfferServiceDataImportPlugin();
        $dataImporterReportTransfer = $productOfferServiceDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertCount(2, $this->tester->getProductOfferServiceQuery());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Arrange
        $productOfferServiceDataImportPlugin = new ProductOfferServiceDataImportPlugin();

        // Act
        $importType = $productOfferServiceDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_PRODUCT_OFFER_SERVICE, $importType);
    }
}
