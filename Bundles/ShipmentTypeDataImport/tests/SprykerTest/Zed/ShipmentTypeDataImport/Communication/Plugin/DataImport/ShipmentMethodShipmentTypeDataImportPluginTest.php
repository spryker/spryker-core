<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport\ShipmentMethodShipmentTypeDataImportPlugin;
use SprykerTest\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ShipmentMethodShipmentTypeDataImportPluginTest
 * Add your own group annotations below this line
 */
class ShipmentMethodShipmentTypeDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SHIPMENT_METHOD_KEY = 'shipment-method-test';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_NAME = 'Delivery test';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_KEY = 'delivery-test';

    /**
     * @uses \Spryker\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportConfig::IMPORT_TYPE_SHIPMENT_METHOD_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const IMPORT_TYPE_SHIPMENT_METHOD_SHIPMENT_TYPE = 'shipment-method-shipment-type';

    /**
     * @var \SprykerTest\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportCommunicationTester
     */
    protected ShipmentTypeDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getShipmentTypeQuery());
        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getShipmentMethodQuery());
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getShipmentMethodQuery());
    }

    /**
     * @return void
     */
    public function testImportImportsDataWhenValidDataSetGiven(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod(
            [
                ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => static::SHIPMENT_METHOD_KEY,
            ],
        );
        $shipmentTypeTransfer = $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::NAME => static::SHIPMENT_TYPE_NAME,
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY,
            ],
        );

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-method-shipment-type/valid_dataset.csv');
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentMethodShipmentTypeDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(1, $this->tester->getShipmentMethodWithShipmentTypeEntityCount());
        $shipmentMethodEntity = $this->tester->getShipmentMethodEntity($shipmentMethodTransfer->getIdShipmentMethodOrFail());
        $this->assertSame(
            $shipmentMethodEntity->getFkShipmentType(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWhenShipmentMethodNotFound(): void
    {
        $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::NAME => static::SHIPMENT_TYPE_NAME,
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY,
            ],
        );
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-method-shipment-type/wrong_shipment_method_key.csv');
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentMethodShipmentTypeDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertEmpty($dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(0, $this->tester->getShipmentMethodWithShipmentTypeEntityCount());
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Could not find Shipment Method by key "not-existed-shipment-method-key"',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWhenShipmentTypeNotFound(): void
    {
        $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::NAME => static::SHIPMENT_TYPE_NAME,
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY,
            ],
        );
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-method-shipment-type/wrong_shipment_type_key.csv');
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentMethodShipmentTypeDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertEmpty($dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(0, $this->tester->getShipmentMethodWithShipmentTypeEntityCount());
    }

    /**
     * @return void
     */
    public function testImportUpdatesDeliveryMethodWhenNullableDeliveryTypeGiven(): void
    {
        // Arrange
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod(
            [
                ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => static::SHIPMENT_METHOD_KEY,
            ],
        );
        $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::NAME => static::SHIPMENT_TYPE_NAME,
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY,
            ],
        );
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-method-shipment-type/nullable_shipment_type.csv');
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentMethodShipmentTypeDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $this->tester->getShipmentMethodWithShipmentTypeEntityCount());
        $shipmentTypeEntity = $this->tester->getShipmentMethodEntity($shipmentMethodTransfer->getIdShipmentMethod());
        $this->assertNull($shipmentTypeEntity->getFkShipmentType());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Arrange
        $shipmentTypeDataImportPlugin = new ShipmentMethodShipmentTypeDataImportPlugin();

        // Act
        $importType = $shipmentTypeDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_SHIPMENT_METHOD_SHIPMENT_TYPE, $importType);
    }
}
