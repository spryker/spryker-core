<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValueDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\MinimumOrderValueDataImport\Communication\Plugin\DataImport\MinimumOrderValueDataImportPlugin;
use Spryker\Zed\MinimumOrderValueDataImport\MinimumOrderValueDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group MinimumOrderValueDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group MinimumOrderValueDataImportPluginTest
 * Add your own group annotations below this line
 */
class MinimumOrderValueDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MinimumOrderValueDataImport\MinimumOrderValueDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->truncateMinimumOrderValues();
        $this->tester->assertMinimumOrderValueTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/minimum_order_value.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MinimumOrderValueDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertMinimumOrderValueTableHasRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MinimumOrderValueDataImportPlugin();
        $this->assertSame(MinimumOrderValueDataImportConfig::IMPORT_TYPE_MINIMUM_ORDER_VALUE, $dataImportPlugin->getImportType());
    }
}
