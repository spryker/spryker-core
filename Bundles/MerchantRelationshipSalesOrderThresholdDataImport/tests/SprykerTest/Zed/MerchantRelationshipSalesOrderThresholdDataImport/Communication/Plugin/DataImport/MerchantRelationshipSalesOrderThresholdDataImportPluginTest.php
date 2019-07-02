<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SpyMerchantRelationshipEntityTransfer;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Communication\Plugin\DataImport\MerchantRelationshipSalesOrderThresholdDataImportPlugin;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\MerchantRelationshipSalesOrderThresholdDataImportConfig;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\HardMinimumThresholdStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithFixedFeeStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithFlexibleFeeStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithMessageStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipSalesOrderThresholdDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group MerchantRelationshipSalesOrderThresholdDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipSalesOrderThresholdDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipSalesOrderThresholdDataImport\MerchantRelationshipSalesOrderThresholdDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->setupDependencies();

        $this->tester->cleanupMerchantRelationshipSalesOrderThresholds();
        $this->tester->assertMerchantRelationshipSalesOrderThresholdTableIsEmtpy();
        $this->createRelatedData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/sales_order_threshold_per_merchant_relationship.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantRelationshipSalesOrderThresholdDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertMerchantRelationshipSalesOrderThresholdTableHasRecords();

        $this->tester->cleanupMerchantRelationshipSalesOrderThresholds();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MerchantRelationshipSalesOrderThresholdDataImportPlugin();
        $this->assertSame(MerchantRelationshipSalesOrderThresholdDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD, $dataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function createRelatedData(): void
    {
        $idMerchant = $this->tester->haveMerchant()->getIdMerchant();

        $this->createMerchantRelationship($idMerchant, 'mr-test-001');
        $this->createMerchantRelationship($idMerchant, 'mr-test-002');
        $this->createMerchantRelationship($idMerchant, 'mr-test-003');
    }

    /**
     * @param int|string $idMerchant
     * @param string $key
     *
     * @return void
     */
    protected function createMerchantRelationship(int $idMerchant, string $key): void
    {
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $this->tester->haveMerchantRelationship([
            SpyMerchantRelationshipEntityTransfer::MERCHANT_RELATIONSHIP_KEY => $key,
            SpyMerchantRelationshipEntityTransfer::FK_MERCHANT => $idMerchant,
            SpyMerchantRelationshipEntityTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @return void
     */
    protected function setupDependencies(): void
    {
        $strategies = [
            new HardMinimumThresholdStrategyPlugin(),
            new SoftMinimumThresholdWithMessageStrategyPlugin(),
            new SoftMinimumThresholdWithFixedFeeStrategyPlugin(),
            new SoftMinimumThresholdWithFlexibleFeeStrategyPlugin(),
        ];

        foreach ($strategies as $strategy) {
            $this->tester->haveSalesOrderThresholdType($strategy->toTransfer());
        }

        $this->tester->setDependency(SalesOrderThresholdDependencyProvider::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY, $strategies);
    }
}
