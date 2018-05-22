<?php
namespace SprykerTest\Zed\MerchantRelationshipDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\MerchantRelationshipDataImport\Communication\Plugin\MerchantRelationshipDataImportPlugin;
use Spryker\Zed\MerchantRelationshipDataImport\MerchantRelationshipDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantRelationshipDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipDataImport\MerchantRelationshipDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->tester->assertDatabaseTableIsEmpty();

        $this->createRelatedData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_relationship.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantRelationshipDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MerchantRelationshipDataImportPlugin();
        $this->assertSame(MerchantRelationshipDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP, $dataImportPlugin->getImportType());
    }

    /**
     * @param int $idCompany
     * @param string $key
     *
     * @return void
     */
    protected function createCompanyBusinessUnit(int $idCompany, string $key): void
    {
        $seedData = [
            'key' => $key,
            'fkCompany' => $idCompany,
            'idCompanyBusinessUnit' => null,
        ];

        $businessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();

        $createdTransfer = $this->tester->getCompanyBusinessUnitFacade()
            ->create($businessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        $this->assertNotNull($createdTransfer->getIdCompanyBusinessUnit());
    }

    /**
     * @return void
     */
    protected function createRelatedData(): void
    {
        $idCompany = $this->tester->haveCompany()->getIdCompany();

        $this->createCompanyBusinessUnit($idCompany, 'ttest-business-unit-1');
        $this->createCompanyBusinessUnit($idCompany, 'ttest-business-unit-2');
        $this->createCompanyBusinessUnit($idCompany, 'ttest-business-unit-3');

        $this->tester->haveMerchant([
            'merchantKey' => 'oryx-merchant-test',
        ]);
    }
}
