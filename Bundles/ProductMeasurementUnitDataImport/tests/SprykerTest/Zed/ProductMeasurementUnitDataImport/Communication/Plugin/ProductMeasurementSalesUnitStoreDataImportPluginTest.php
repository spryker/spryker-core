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
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementBaseUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitStoreDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnitDataImport
 * @group Communication
 * @group Plugin
 * @group ProductMeasurementSalesUnitStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductMeasurementSalesUnitStoreDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(
            DataImportDependencyProvider::DATA_IMPORTER_PLUGINS,
            [
                new ProductMeasurementUnitDataImportPlugin(),
                new ProductMeasurementBaseUnitDataImportPlugin(),
                new ProductMeasurementSalesUnitDataImportPlugin(),
                new ProductMeasurementSalesUnitStoreDataImportPlugin(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_measurement_sales_unit_store.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productMeasurementSalesUnitStoreDataImportPlugin = new ProductMeasurementSalesUnitStoreDataImportPlugin();
        $dataImporterReportTransfer = $productMeasurementSalesUnitStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertMeasurementSalesUnitStoreContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productMeasurementSalesUnitStoreDataImportPlugin = new ProductMeasurementSalesUnitStoreDataImportPlugin();
        $this->assertSame(
            ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_SALES_UNIT_STORE,
            $productMeasurementSalesUnitStoreDataImportPlugin->getImportType()
        );
    }
}
