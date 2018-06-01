<?php
namespace SprykerTest\Zed\ProductPackagingUnitDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\ProductPackagingUnitTypeDataImportPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnitDataImport
 * @group Communication
 * @group Plugin
 * @group ProductPackagingUnitTypeDataImportPluginTest
 */
class ProductPackagingUnitTypeDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->tester->assertDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_packaging_unit_type.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new ProductPackagingUnitTypeDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new ProductPackagingUnitTypeDataImportPlugin();
        $this->assertSame(ProductPackagingUnitDataImportConfig::IMPORT_TYPE_PRODUCT_PACKAGING_UNIT_TYPE, $dataImportPlugin->getImportType());
    }
}
