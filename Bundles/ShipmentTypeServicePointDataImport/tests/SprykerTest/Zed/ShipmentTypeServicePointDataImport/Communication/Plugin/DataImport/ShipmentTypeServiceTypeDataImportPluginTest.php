<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePointDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Communication\Plugin\DataImport\ShipmentTypeServiceTypeDataImportPlugin;
use SprykerTest\Zed\ShipmentTypeServicePointDataImport\ShipmentTypeServicePointDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeServicePointDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ShipmentTypeServiceTypeDataImportPluginTest
 * Add your own group annotations below this line
 */
class ShipmentTypeServiceTypeDataImportPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ShipmentTypeServicePointDataImport\ShipmentTypeServicePointDataImportConfig::IMPORT_TYPE_SHIPMENT_TYPE_SERVICE_TYPE
     *
     * @var string
     */
    protected const IMPORT_TYPE_SHIPMENT_TYPE_SERVICE_TYPE = 'shipment-type-service-type';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_KEY_1 = 'test-shipment-type-key-1';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_KEY_2 = 'test-shipment-type-key-2';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_KEY_3 = 'test-shipment-type-key-3';

    /**
     * @var string
     */
    protected const TEST_SERVICE_TYPE_KEY_1 = 'test-service-type-key-1';

    /**
     * @var string
     */
    protected const TEST_SERVICE_TYPE_KEY_2 = 'test-service-type-key-2';

    /**
     * @var \SprykerTest\Zed\ShipmentTypeServicePointDataImport\ShipmentTypeServicePointDataImportCommunicationTester
     */
    protected ShipmentTypeServicePointDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureShipmentTypeServiceTypeTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->cleanUpTestData();
    }

    /**
     * @group test
     *
     * @return void
     */
    public function testImportPersistsShipmentTypeServiceTypeRelations(): void
    {
        // Arrange
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => static::TEST_SHIPMENT_TYPE_KEY_1]);
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => static::TEST_SHIPMENT_TYPE_KEY_2]);
        $shipmentTypeTransfer3 = $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => static::TEST_SHIPMENT_TYPE_KEY_3]);
        $serviceTypeTransfer1 = $this->tester->haveServiceType([ServiceTypeTransfer::KEY => static::TEST_SERVICE_TYPE_KEY_1]);
        $serviceTypeTransfer2 = $this->tester->haveServiceType([ServiceTypeTransfer::KEY => static::TEST_SERVICE_TYPE_KEY_2]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            codecept_data_dir() . 'import/shipment-type-service-type/valid_dataset.csv',
        );
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentTypeServiceTypeDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(3, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(3, $this->tester->getShipmentTypeServiceTypeQuery());
        $this->assertNotNull($this->tester->findShipmentTypeServiceType($shipmentTypeTransfer1->getUuidOrFail(), $serviceTypeTransfer1->getUuidOrFail()));
        $this->assertNotNull($this->tester->findShipmentTypeServiceType($shipmentTypeTransfer2->getUuidOrFail(), $serviceTypeTransfer1->getUuidOrFail()));
        $this->assertNotNull($this->tester->findShipmentTypeServiceType($shipmentTypeTransfer3->getUuidOrFail(), $serviceTypeTransfer2->getUuidOrFail()));
    }

    /**
     * @group test
     *
     * @return void
     */
    public function testImportUpdatesShipmentTypeServiceTypeRelationsWhenDuplicatedShipmentTypeKeyProvided(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => static::TEST_SHIPMENT_TYPE_KEY_1]);
        $serviceTypeTransfer1 = $this->tester->haveServiceType([ServiceTypeTransfer::KEY => static::TEST_SERVICE_TYPE_KEY_1]);
        $serviceTypeTransfer2 = $this->tester->haveServiceType([ServiceTypeTransfer::KEY => static::TEST_SERVICE_TYPE_KEY_2]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            codecept_data_dir() . 'import/shipment-type-service-type/valid_dataset_with_duplicated_shipment_type_key.csv',
        );
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentTypeServiceTypeDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(1, $this->tester->getShipmentTypeServiceTypeQuery());
        $this->assertNotNull($this->tester->findShipmentTypeServiceType($shipmentTypeTransfer->getUuidOrFail(), $serviceTypeTransfer2->getUuidOrFail()));
        $this->assertNull($this->tester->findShipmentTypeServiceType($shipmentTypeTransfer->getUuidOrFail(), $serviceTypeTransfer1->getUuidOrFail()));
    }

    /**
     * @return void
     */
    public function testImportReturnsErrorWhenShipmentTypeKeyIsMissing(): void
    {
        $this->tester->haveServiceType([ServiceTypeTransfer::KEY => static::TEST_SERVICE_TYPE_KEY_1]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            codecept_data_dir() . 'import/shipment-type-service-type/missing_shipment_type_key_dataset.csv',
        );
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentTypeServiceTypeDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            '"shipment_type_key" is required.',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportReturnsErrorWhenServiceTypeKeyIsMissing(): void
    {
        $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => static::TEST_SHIPMENT_TYPE_KEY_1]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            codecept_data_dir() . 'import/shipment-type-service-type/missing_service_type_key_dataset.csv',
        );
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentTypeServiceTypeDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            '"service_type_key" is required.',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportReturnsErrorWhenShipmentTypeKeyIsInvalid(): void
    {
        $this->tester->haveServiceType([ServiceTypeTransfer::KEY => static::TEST_SERVICE_TYPE_KEY_1]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            codecept_data_dir() . 'import/shipment-type-service-type/invalid_shipment_type_key_dataset.csv',
        );
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentTypeServiceTypeDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Could not find shipment type by key "invalid-shipment-type-key"',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportReturnsErrorWhenServiceTypeKeyIsInvalid(): void
    {
        $this->tester->haveShipmentType([ShipmentTypeTransfer::KEY => static::TEST_SHIPMENT_TYPE_KEY_1]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            codecept_data_dir() . 'import/shipment-type-service-type/invalid_service_type_key_dataset.csv',
        );
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentTypeServiceTypeDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shipmentMethodShipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(0, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Could not find service type by key "invalid-service-type-key"',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsCorrectImportTypeName(): void
    {
        // Arrange
        $shipmentMethodShipmentTypeStoreDataImportPlugin = new ShipmentTypeServiceTypeDataImportPlugin();

        // Act
        $importType = $shipmentMethodShipmentTypeStoreDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_SHIPMENT_TYPE_SERVICE_TYPE, $importType);
    }
}
