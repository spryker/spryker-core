<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport\ProductPackagingUnitDataImportPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportConfig;

/**
 * Auto-generated group annotations
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
    protected const EXPECTED_AMOUNT = 2;

    protected const PRODUCT_SKUS = [
        'concrete_sku_example_1',
        'concrete_sku_example_2',
        'concrete_sku_example_3',
        'concrete_sku_example_4',
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
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->truncateProductPackagingUnits();
        $this->tester->truncateProductPackagingUnitTypes();
        $this->tester->truncateProductPackagingLeadProducts();
        $this->tester->assertProductPackagingUnitTableIsEmtpy();
        $this->tester->assertProductPackagingUnitTypeTableIsEmtpy();
        $this->tester->assertProductPackagingLeadProductTableIsEmtpy();

        $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE_DEFAULT]);
        $this->tester->haveProductPackagingUnitType([SpyProductPackagingUnitTypeEntityTransfer::NAME => static::PACKAGING_TYPE]);
        $this->createTestProducts();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_packaging_unit.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new ProductPackagingUnitDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertProductPackagingLeadProductTableHasRecords();
        $this->cleanupTestProducts();
        $this->tester->assertProductPackagingUnitTableHasRecords();
        $this->tester->assertProductPackagingUnitAmountTableHasRecords(static::EXPECTED_AMOUNT);
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
            $productConcreteTransfer = $this->tester->haveProduct([SpyProductEntityTransfer::SKU => $sku]);
            $this->productAbstractIds[] = $productConcreteTransfer->getFkProductAbstract();
        }
    }

    /**
     * @return void
     */
    protected function cleanupTestProducts(): void
    {
        foreach ($this->productAbstractIds as $abstractId) {
            $this->tester->cleanupProductPackagingLeadProduct($abstractId);
        }
    }
}
