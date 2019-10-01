<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
 *
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
        $this->tester->ensureMeasurementSalesUnitStoreIsEmpty();

        $dataDir = codecept_data_dir();
        $this->tester->importMeasurementUnitData($dataDir);
        $this->tester->importMeasurementBaseUnitData($dataDir);
        $this->tester->importMeasurementSalesUnitData($dataDir);
        $dataImporterReportTransfer = $this->tester->importMeasurementSalesUnitStoreData($dataDir);

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
