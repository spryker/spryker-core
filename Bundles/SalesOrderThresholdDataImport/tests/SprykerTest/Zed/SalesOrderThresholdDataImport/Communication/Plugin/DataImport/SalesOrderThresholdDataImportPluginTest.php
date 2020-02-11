<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\SalesOrderThresholdDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\HardMinimumThresholdStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithMessageStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdDependencyProvider;
use Spryker\Zed\SalesOrderThresholdDataImport\Communication\Plugin\DataImport\SalesOrderThresholdDataImportPlugin;
use Spryker\Zed\SalesOrderThresholdDataImport\SalesOrderThresholdDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderThresholdDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group SalesOrderThresholdDataImportPluginTest
 * Add your own group annotations below this line
 */
class SalesOrderThresholdDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesOrderThresholdDataImport\SalesOrderThresholdDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->setupDependencies();

        $this->tester->truncateSalesOrderThresholds();
        $this->tester->assertSalesOrderThresholdTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/sales_order_threshold.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new SalesOrderThresholdDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertSalesOrderThresholdTableHasRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new SalesOrderThresholdDataImportPlugin();
        $this->assertSame(SalesOrderThresholdDataImportConfig::IMPORT_TYPE_SALES_ORDER_THRESHOLD, $dataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function setupDependencies(): void
    {
        $strategies = [
            new HardMinimumThresholdStrategyPlugin(),
            new SoftMinimumThresholdWithMessageStrategyPlugin(),
        ];

        foreach ($strategies as $strategy) {
            $this->tester->haveSalesOrderThresholdType($strategy->toTransfer());
        }

        $this->tester->setDependency(SalesOrderThresholdDependencyProvider::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY, $strategies);
    }
}
