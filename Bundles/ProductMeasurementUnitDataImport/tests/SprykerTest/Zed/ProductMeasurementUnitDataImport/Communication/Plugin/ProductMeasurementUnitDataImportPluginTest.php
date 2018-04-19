<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportConfig;

class ProductMeasurementUnitDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
{
    $this->tester->ensureDatabaseTableIsEmpty();

    $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
    $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_measurement_unit.csv');

    $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
    $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

    $productMeasurementUnitDataImportPlugin = new ProductMeasurementUnitDataImportPlugin();
        $dataImporterReportTransfer = $productMeasurementUnitDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productMeasurementUnitDataImportPlugin = new ProductMeasurementUnitDataImportPlugin();
        $this->assertSame(
            ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_UNIT,
            $productMeasurementUnitDataImportPlugin->getImportType()
        );
    }
}
