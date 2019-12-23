<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport\ProductPackagingUnitDataImportPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportConfig;
use Throwable;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnitDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ProductPackagingUnitDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitDataImportPluginTest extends Unit
{
    protected const EXPECTED_AMOUNT = 4;

    protected const PRODUCT_SKUS = [
        'concrete_sku_example_1',
        'concrete_sku_example_2',
        'concrete_sku_example_3',
        'concrete_sku_example_4',
        'concrete_sku_example_5',
    ];

    protected const PACKAGING_TYPE_DEFAULT = 'item';
    protected const PACKAGING_TYPE = 'box';

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected $productAbstractIds = [];

    /**
     * @var array
     */
    protected $productConcreteIds = [];

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->truncateProductPackagingUnits();
        $this->tester->truncateProductPackagingUnitTypes();
        $this->tester->assertProductPackagingUnitTableIsEmtpy();
        $this->tester->assertProductPackagingUnitTypeTableIsEmtpy();

        $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE_DEFAULT]);
        $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);
        $this->createTestProducts();
        $this->createTestMeasurementSalesUnits();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_packaging_unit.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new ProductPackagingUnitDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertProductPackagingUnitTableHasRecords(static::EXPECTED_AMOUNT);
        $this->cleanupTestProducts();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new ProductPackagingUnitDataImportPlugin();
        $this->assertSame(ProductPackagingUnitDataImportConfig::IMPORT_TYPE_PRODUCT_PACKAGING_UNIT, $dataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function createTestProducts(): void
    {
        foreach (static::PRODUCT_SKUS as $sku) {
            try {
                $productConcreteTransfer = $this->tester->haveProduct([SpyProductEntityTransfer::SKU => $sku]);
            } catch (Throwable $throwable) {
                $productConcreteTransfer = $this->tester->getLocator()->product()->facade()->getProductConcrete($sku);
            }

            $this->productAbstractIds[$sku] = $productConcreteTransfer->getFkProductAbstract();
            $this->productConcreteIds[$sku] = $productConcreteTransfer->getIdProductConcrete();
        }
    }

    /**
     * @return void
     */
    protected function createTestMeasurementSalesUnits(): void
    {
        $code = 'MYCODE' . random_int(1, 100);
        $productMeasurementUnitTransfer = $this->tester->haveProductMeasurementUnit([
            SpyProductMeasurementUnitEntityTransfer::CODE => $code,
        ]);

        foreach (static::PRODUCT_SKUS as $sku) {
            $productMeasurementBaseUnitTransfer = $this->tester->haveProductMeasurementBaseUnit(
                $this->productAbstractIds[$sku],
                $productMeasurementUnitTransfer->getIdProductMeasurementUnit()
            );

            $this->tester->haveProductMeasurementSalesUnit(
                $this->productConcreteIds[$sku],
                $productMeasurementUnitTransfer->getIdProductMeasurementUnit(),
                $productMeasurementBaseUnitTransfer->getIdProductMeasurementBaseUnit()
            );
        }
    }

    /**
     * @return void
     */
    protected function cleanupTestProducts(): void
    {
        foreach ($this->productConcreteIds as $concreteId) {
            $this->tester->cleanupProductPackagingUnitProduct($concreteId);
        }
    }
}
