<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport\ShipmentTypeDataImportPlugin;
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
 * @group ShipmentTypeDataImportPluginTest
 * Add your own group annotations below this line
 */
class ShipmentTypeDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_KEY = 'pickup';

    /**
     * @uses \Spryker\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportConfig::IMPORT_TYPE_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const IMPORT_TYPE_SHIPMENT_TYPE = 'shipment-type';

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

        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getShipmentTypeQuery());
    }

    /**
     * @return void
     */
    public function testImportImportsDataWhenValidDataSetGiven(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-type/valid_dataset.csv');
        $shipmentTypeDataImportPlugin = new ShipmentTypeDataImportPlugin();
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $shipmentTypeDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(2, $this->tester->getShipmentTypeEntityCount());
    }

    /**
     * @return void
     */
    public function testImportUpdatesEntityWhenDuplicatedKeyGiven(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-type/duplicated_keys.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $shipmentTypeDataImportPlugin = new ShipmentTypeDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shipmentTypeDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame(2, $dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(1, $this->tester->getShipmentTypeEntityCount());

        $shipmenTypeEntity = $this->tester->getShipmentTypeEntity(static::TEST_SHIPMENT_TYPE_KEY);
        $this->assertSame('Pickup', $shipmenTypeEntity->getName());
        $this->assertFalse($shipmenTypeEntity->getIsActive());
    }

    /**
     * @return void
     */
    public function testImportDoesntImportDataWhenRequiredFieldsAreMissing(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/shipment-type/missed_required_fields.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $shipmentTypeDataImportPlugin = new ShipmentTypeDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $shipmentTypeDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertEmpty($dataImporterReportTransfer->getImportedDataSetCount());
        $this->assertSame(0, $this->tester->getShipmentTypeEntityCount());
        $this->assertCount(3, $dataImporterReportTransfer->getMessages());

        $dataImporterMessagesIterator = $dataImporterReportTransfer->getMessages()->getIterator();
        $this->assertStringContainsString(
            '"key" is required',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            '"name" is required',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
        $dataImporterMessagesIterator->next();
        $this->assertStringContainsString(
            '"is_active" is required',
            $dataImporterMessagesIterator->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedType(): void
    {
        // Arrange
        $shipmentTypeDataImportPlugin = new ShipmentTypeDataImportPlugin();

        // Act
        $importType = $shipmentTypeDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(static::IMPORT_TYPE_SHIPMENT_TYPE, $importType);
    }
}
