<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductMeasurementUnitDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
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
     * @var bool
     */
    protected static $neededDataAdded = false;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            DataImportDependencyProvider::DATA_IMPORTER_PLUGINS,
            [
                new ProductMeasurementUnitDataImportPlugin(),
                new ProductMeasurementBaseUnitDataImportPlugin(),
                new ProductMeasurementSalesUnitDataImportPlugin(),
                new ProductMeasurementSalesUnitStoreDataImportPlugin(),
            ],
        );

        if (!static::$neededDataAdded) {
            $idProductAbstract = $this->getProductFacade()->findProductAbstractIdBySku('testing-sku-197888');
            if (!$idProductAbstract) {
                $idProductAbstract = ($this->tester->haveProductAbstract(['sku' => 'testing-sku-197888']))
                    ->getIdProductAbstract();
            }
            $concreteSkus = [
                'testing-sku-2289711',
                'testing-sku-2289712',
                'testing-sku-2289713',
                'testing-sku-2289714',
                'testing-sku-2289715',
                'testing-sku-2289716',
                'testing-sku-2289717',
                'testing-sku-2289718',
                'testing-sku-2289719',
            ];

            foreach ($concreteSkus as $concreteSku) {
                $this->tester->haveProductConcrete(
                    ['sku' => $concreteSku, 'fkProductAbstract' => $idProductAbstract],
                );
            }
            static::$neededDataAdded = true;
        }
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureMeasurementSalesUnitStoreIsEmpty();
        $this->tester->ensureMeasurementSalesUnitIsEmpty();

        $dataDir = codecept_data_dir();
        $this->tester->importMeasurementUnitData($dataDir);
        $this->tester->importMeasurementBaseUnitData($dataDir);
        $dataImporterReportTransfer = $this->tester->importMeasurementSalesUnitData($dataDir);

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
            $productMeasurementSalesUnitDataImportPlugin->getImportType(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected function getProductFacade(): ProductFacadeInterface
    {
        return $this->tester->getLocator()->product()->facade();
    }
}
