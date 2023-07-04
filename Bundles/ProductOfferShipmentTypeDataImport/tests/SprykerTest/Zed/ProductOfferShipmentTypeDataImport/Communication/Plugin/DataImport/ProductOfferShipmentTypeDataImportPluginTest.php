<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferShipmentTypeDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerTest\Zed\ProductOfferShipmentTypeDataImport\ProductOfferShipmentTypeDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShipmentTypeDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ProductOfferShipmentTypeDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferShipmentTypeDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_KEY_1 = 'shipment_type_key_1';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_KEY_2 = 'shipment_type_key_2';

    /**
     * @var string
     */
    protected const NOT_EXISTING_SHIPMENT_TYPE_KEY = 'not_existing_shipment_type_key';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_1 = 'product_offer_reference_1';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_2 = 'product_offer_reference_2';

    /**
     * @var string
     */
    protected const NOT_EXISTING_PRODUCT_OFFER_REFERENCE = 'not_existing_product_offer_reference';

    /**
     * @uses \Spryker\Zed\ProductOfferShipmentTypeDataImport\ProductOfferShipmentTypeDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const IMPORT_TYPE_PRODUCT_OFFER_SHIPMENT_TYPE = 'product-offer-shipment-type';

    /**
     * @var \SprykerTest\Zed\ProductOfferShipmentTypeDataImport\ProductOfferShipmentTypeDataImportCommunicationTester
     */
    protected ProductOfferShipmentTypeDataImportCommunicationTester $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected ProductConcreteTransfer $productConcreteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->cleanUpData();
        $this->productConcreteTransfer = $this->tester->haveProduct();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->cleanUpStaticCacheData();
        $this->tester->cleanUpData();
    }

    /**
     * @return void
     */
    public function testImportImportsValidProductOfferShipmentTypeDataSet(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_1,
            ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_2,
            ]);
        $this->tester->haveProductOffer([
                ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
                ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransfer->getSku(),
            ]);

        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2,
        ]);

        $dataImportConfigurationTransfer = $this->createDataImporterConfigurationTransfer(
            'valid_data_set.csv',
        );

        // Act
        $dataImporterReportTransfer = $this->tester->createProductOfferShipmentTypeDataImportPlugin()
            ->import($dataImportConfigurationTransfer);

        $productOfferShipmentTypeEntitiesCount = $this->tester->getProductOfferShipmentTypeEntitiesByShipmentTypeKeysCount([
            $shipmentTypeTransfer1->getKey(),
            $shipmentTypeTransfer2->getKey(),
        ]);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(4, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(4, $productOfferShipmentTypeEntitiesCount);
    }

    /**
     * @group test
     * @return void
     */
    public function testImportDoesntImportDataWithAnEmptyProductOfferReference(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_1,
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_2,
        ]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransfer->getSku(),
        ]);
        $dataImportConfigurationTransfer = $this->createDataImporterConfigurationTransfer(
            'empty_product_offer_reference.csv',
        );

        // Act
        $dataImporterReportTransfer = $this->tester->createProductOfferShipmentTypeDataImportPlugin()
            ->import($dataImportConfigurationTransfer);

        $productOfferShipmentTypeEntitiesCount = $this->tester->getProductOfferShipmentTypeEntitiesByShipmentTypeKeysCount([
            $shipmentTypeTransfer1->getKey(),
            $shipmentTypeTransfer2->getKey(),
        ]);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getExpectedImportableDataSetCount());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(1, $productOfferShipmentTypeEntitiesCount);
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Missing required "product_offer_reference" field.',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWithAnEmptyShipmentTypeKey(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_1,
        ]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransfer->getSku(),
        ]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2,
            ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransfer->getSku(),
        ]);
        $dataImportConfigurationTransfer = $this->createDataImporterConfigurationTransfer(
            'empty_shipment_type_key.csv',
        );

        // Act
        $dataImporterReportTransfer = $this->tester->createProductOfferShipmentTypeDataImportPlugin()
            ->import($dataImportConfigurationTransfer);

        $productOfferShipmentTypeEntitiesCount = $this->tester->getProductOfferShipmentTypeEntitiesByShipmentTypeKeysCount([
            $shipmentTypeTransfer->getKey(),
        ]);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getExpectedImportableDataSetCount());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(1, $productOfferShipmentTypeEntitiesCount);
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Missing required "shipment_type_key" field.',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWithNotExistingProductOfferReference(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_1,
        ]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_2,
        ]);

        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransfer->getSku(),
        ]);
        $dataImportConfigurationTransfer = $this->createDataImporterConfigurationTransfer(
            'not_existing_product_offer_reference.csv',
        );

        // Act
        $dataImporterReportTransfer = $this->tester->createProductOfferShipmentTypeDataImportPlugin()
            ->import($dataImportConfigurationTransfer);

        $productOfferShipmentTypeEntitiesCount = $this->tester->getProductOfferShipmentTypeEntitiesByShipmentTypeKeysCount([
            $shipmentTypeTransfer1->getKey(),
            $shipmentTypeTransfer2->getKey(),
        ]);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getExpectedImportableDataSetCount());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(1, $productOfferShipmentTypeEntitiesCount);
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Could not find product offer by product offer reference "' . static::NOT_EXISTING_PRODUCT_OFFER_REFERENCE . '"',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWithNotExistingShipmentTypeKey(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_1,
        ]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_1,
            ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransfer->getSku(),
        ]);
        $this->tester->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2,
            ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransfer->getSku(),
        ]);
        $dataImportConfigurationTransfer = $this->createDataImporterConfigurationTransfer(
            'not_existing_shipment_type_key.csv',
        );

        // Act
        $dataImporterReportTransfer = $this->tester->createProductOfferShipmentTypeDataImportPlugin()
            ->import($dataImportConfigurationTransfer);

        $productOfferShipmentTypeEntitiesCount = $this->tester->getProductOfferShipmentTypeEntitiesByShipmentTypeKeysCount([
            static::NOT_EXISTING_SHIPMENT_TYPE_KEY,
            $shipmentTypeTransfer1->getKey(),
        ]);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getExpectedImportableDataSetCount());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(1, $productOfferShipmentTypeEntitiesCount);
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Could not find shipment type by key "' . static::NOT_EXISTING_SHIPMENT_TYPE_KEY . '"',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Act
        $importType = $this->tester->createProductOfferShipmentTypeDataImportPlugin()->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_PRODUCT_OFFER_SHIPMENT_TYPE, $importType);
    }

    /**
     * @param string $importFileName
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function createDataImporterConfigurationTransfer(string $importFileName): DataImporterConfigurationTransfer
    {
        return (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration(
                (new DataImporterReaderConfigurationTransfer())
                    ->setFileName(codecept_data_dir() . 'import/product-offer-shipment-type/' . $importFileName),
            );
    }
}
