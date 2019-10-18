<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ShipmentDataImport\Communication\Plugin\ShipmentMethodPriceDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDataImport
 * @group Communication
 * @group Plugin
 * @group ShipmentMethodPriceDataImportPluginTest
 * Add your own group annotations below this line
 */
class ShipmentMethodPriceDataImportPluginTest extends Unit
{
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var \SprykerTest\Zed\ShipmentDataImport\ShipmentDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsShipmentMethodPrices(): void
    {
        //Arrange
        $this->tester->ensureShipmentMethodPriceTableIsEmpty();
        $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $this->tester->haveCurrency([
            CurrencyTransfer::CODE => 'EUR',
        ]);
        $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'spryker_dummy_shipment-standard',
        ]);
        $this->tester->haveShipmentMethod([
            ShipmentMethodTransfer::SHIPMENT_METHOD_KEY => 'spryker_dummy_shipment-express',
        ]);
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shipment_price.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $shipmentStoreDataImportPlugin = new ShipmentMethodPriceDataImportPlugin();

        //Act
        $dataImporterReportTransfer = $shipmentStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        //Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess(), 'Data import should finish successfully');

        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of shipments is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }
}
