<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\SalesReturnDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\SalesReturnDataImport\Communication\Plugin\SalesReturnReasonDataImportPlugin;
use Spryker\Zed\SalesReturnDataImport\SalesReturnDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturnDataImport
 * @group Communication
 * @group Plugin
 * @group SalesReturnReasonDataImportPluginTest
 * Add your own group annotations below this line
 * @group SalesReturn
 */
class SalesReturnReasonDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturnDataImport\SalesReturnDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureSalesReturnReasonTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/sales_return_reason.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $salesReturnReasonDataImportPlugin = new SalesReturnReasonDataImportPlugin();
        $dataImporterReportTransfer = $salesReturnReasonDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertSalesReturnReasonDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $salesReturnReasonDataImportPlugin = new SalesReturnReasonDataImportPlugin();

        // Assert
        $this->assertSame(SalesReturnDataImportConfig::IMPORT_TYPE_SALES_RETURN_REASON, $salesReturnReasonDataImportPlugin->getImportType());
    }
}
