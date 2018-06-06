<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitDataImport\Communication\Plugin;

use Codeception\Test\Unit;
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
 * @group ProductMeasurementBaseUnitDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductMeasurementBaseUnitDataImportPluginTest extends Unit
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
        $this->tester->ensureMeasurementSalesUnitStoreIsEmpty();
        $this->tester->ensureMeasurementSalesUnitIsEmpty();
        $this->tester->ensureMeasurementBaseUnitIsEmpty();

        $dataDir = codecept_data_dir();
        $this->tester->importMeasurementUnitData($dataDir);
        $dataImporterReportTransfer = $this->tester->importMeasurementBaseUnitData($dataDir);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertMeasurementBaseUnitContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productMeasurementBaseUnitDataImportPlugin = new ProductMeasurementBaseUnitDataImportPlugin();
        $this->assertSame(
            ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_BASE_UNIT,
            $productMeasurementBaseUnitDataImportPlugin->getImportType()
        );
    }
}
