<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantRelationshipMinimumOrderValueDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SpyMerchantRelationshipEntityTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Communication\Plugin\DataImport\MerchantRelationshipMinimumOrderValueDataImportPlugin;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\MerchantRelationshipMinimumOrderValueDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipMinimumOrderValueDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group MerchantRelationshipMinimumOrderValueDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipMinimumOrderValueDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipMinimumOrderValueDataImport\MerchantRelationshipMinimumOrderValueDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->cleanupMerchantRelationshipMinimumOrderValues();
        $this->tester->assertMerchantRelationshipMinimumOrderValueTableIsEmtpy();
        $this->createRelatedData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/minimum_order_value_per_merchant_relationship.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantRelationshipMinimumOrderValueDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertMerchantRelationshipMinimumOrderValueTableHasRecords();

        $this->tester->cleanupMerchantRelationshipMinimumOrderValues();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MerchantRelationshipMinimumOrderValueDataImportPlugin();
        $this->assertSame(MerchantRelationshipMinimumOrderValueDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP_MINIMUM_ORDER_VALUE, $dataImportPlugin->getImportType());
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
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit();

        $this->tester->haveMerchantRelationship([
            SpyMerchantRelationshipEntityTransfer::MERCHANT_RELATIONSHIP_KEY => $key,
            SpyMerchantRelationshipEntityTransfer::FK_MERCHANT => $idMerchant,
            SpyMerchantRelationshipEntityTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);
    }
}
