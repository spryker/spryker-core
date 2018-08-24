<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SpyCompanyEntityTransfer;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRoleDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\CompanyRoleDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyRoleDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group CompanyRoleDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyRoleDataImportPluginTest extends Unit
{
    protected const COMPANY_KEY = 'Test_ltd';

    /**
     * @var \SprykerTest\Zed\CompanyRoleDataImport\CompanyRoleDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->truncateCompanyRoles();
        $this->tester->assertCompanyRoleTableIsEmtpy();
        $this->prepareTestData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_role.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new CompanyRoleDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyRoleTableHasRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new CompanyRoleDataImportPlugin();
        $this->assertSame(CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_ROLE, $dataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function prepareTestData(): void
    {
        $companyTransfer = $this->tester->haveCompany([
            SpyCompanyEntityTransfer::KEY => static::COMPANY_KEY,
        ]);
    }
}
