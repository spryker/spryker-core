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
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnitDataImport
 * @group Communication
 * @group Plugin
 * @group ProductMeasurementSalesUnitDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductMeasurementSalesUnitDataImportPluginTest extends Unit
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
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_measurement_sales_unit.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productMeasurementSalesUnitDataImportPlugin = new ProductMeasurementSalesUnitDataImportPlugin();
        $dataImporterReportTransfer = $productMeasurementSalesUnitDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertMeasurementSalesUnitContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productMeasurementSalesUnitDataImportPlugin = new ProductMeasurementSalesUnitDataImportPlugin();
        $this->assertSame(
            ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_SALES_UNIT,
            $productMeasurementSalesUnitDataImportPlugin->getImportType()
        );
    }
}
