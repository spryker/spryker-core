<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport\ShipmentTypeStoreDataImportPlugin;
use SprykerTest\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentType
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ShipmentTypeStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class ShipmentTypeStoreDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_TEST = 'TEST';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_NAME_1 = 'Delivery test';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_NAME_2 = 'Pickup test';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_CODE_1 = 'delivery-test';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_CODE_2 = 'pickup-test';

    /**
     * @uses \Spryker\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportConfig::IMPORT_TYPE_SHIPMENT_TYPE_STORE
     *
     * @var string
     */
    protected const IMPORT_TYPE_SHIPMENT_TYPE_STORE = 'shipment-type-store';

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
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getShipmentTypeStoreQuery());
    }

    /**
     * @return void
     */
    public function testImportImportsDataWhenValidDataSetGiven(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_TEST]);

        $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::NAME => static::SHIPMENT_TYPE_NAME_1,
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_CODE_1,
            ],
        );
        $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::NAME => static::SHIPMENT_TYPE_NAME_2,
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_CODE_2,
            ],
        );

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-type-store/valid_dataset.csv');
        $shipmentTypeStoreDataImportPlugin = new ShipmentTypeStoreDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(2, $this->tester->getShipmentTypeStoreEntityCount());
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWhenShipmentKeyDoesntExist(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_TEST]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-type-store/wrong_shipment_type_key.csv');
        $shipmentTypeStoreDataImportPlugin = new ShipmentTypeStoreDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertEmpty($dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Could not find Shipment Type by key "not-existed-shipment-type-key"',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWhenStoreDoesntExist(): void
    {
        // Arrange
        $this->tester->haveShipmentType(
            [
                ShipmentTypeTransfer::NAME => static::SHIPMENT_TYPE_NAME_1,
                ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_CODE_1,
            ],
        );

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-type-store/wrong_store_name.csv');
        $shipmentTypeStoreDataImportPlugin = new ShipmentTypeStoreDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertEmpty($dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            'Could not find Store by name "NOT EXISTED STORE"',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWhenShipmentKeyIsMissing(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_TEST]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-type-store/missing_shipment_type_key.csv');
        $shipmentTypeStoreDataImportPlugin = new ShipmentTypeStoreDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentTypeStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertEmpty($dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertCount(1, $dataImporterReportTransfer->getMessages());
        $this->assertStringContainsString(
            '"shipment_type_key" is required',
            $dataImporterReportTransfer->getMessages()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Arrange
        $shipmentTypeDataImportPlugin = new ShipmentTypeStoreDataImportPlugin();

        // Act
        $importType = $shipmentTypeDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_SHIPMENT_TYPE_STORE, $importType);
    }
}
