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
use Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport\ProductPackagingUnitTypeDataImportPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnitDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ProductPackagingUnitTypeDataImportPluginTest
 * Add your own group annotations below this line
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
        $this->tester->truncateProductPackagingUnits();
        $this->tester->truncateProductPackagingUnitTypes();
        $this->tester->assertProductPackagingUnitTypeTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_packaging_unit_type.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new ProductPackagingUnitTypeDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertProductPackagingUnitTypeTableHasRecords();
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
