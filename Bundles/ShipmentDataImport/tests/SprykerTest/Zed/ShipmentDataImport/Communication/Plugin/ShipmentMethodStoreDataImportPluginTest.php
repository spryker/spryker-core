<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShipmentDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ShipmentDataImport\Communication\Plugin\ShipmentMethodStoreDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDataImport
 * @group Communication
 * @group Plugin
 * @group ShipmentMethodStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class ShipmentMethodStoreDataImportPluginTest extends Unit
{
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var \SprykerTest\Zed\ShipmentDataImport\ShipmentDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsShipmentMethodStore(): void
    {
        //Arrange
        $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $this->tester->haveStore([
            StoreTransfer::NAME => 'AT',
        ]);

        $shipmentCarrierTransfer = $this->tester->haveShipmentCarrier();
        $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'method-1',
            ShipmentMethodTransfer::FK_SHIPMENT_CARRIER => $shipmentCarrierTransfer->getIdShipmentCarrier(),
        ]);

        $this->tester->ensureShipmentMethodStoreTableIsEmpty();
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shipment_method_store.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $shipmentStoreDataImportPlugin = new ShipmentMethodStoreDataImportPlugin();

        //Act
        $dataImporterReportTransfer = $shipmentStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        //Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess(), 'Data import should finish successfully');

        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of price product schedules is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }
}
