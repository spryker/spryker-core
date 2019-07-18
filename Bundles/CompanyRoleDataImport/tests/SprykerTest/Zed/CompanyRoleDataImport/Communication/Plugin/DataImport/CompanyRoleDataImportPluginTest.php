<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
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
class CompanyRoleDataImportPluginTest extends AbstractCompanyRoleDataImportMock
{
    /**
     * @return void
     */
    public function testImportCompanyRoleData(): void
    {
        $this->tester->truncateCompanyRoles();
        $this->tester->assertCompanyRoleTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/company_role.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $dataImporterReportTransfer = $this->getCompanyRoleDataImportPlugin()->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyRoleTableHasRecords();
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testImportCompanyRoleWithInvalidCompany(): void
    {
        $this->tester->truncateCompanyRoles();
        $this->tester->assertCompanyRoleTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/company_role_with_invalid_company.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $dataImporterReportTransfer = $this->getCompanyRoleDataImportPlugin()->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyRoleTableHasRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyRoleDataImportPlugin = new CompanyRoleDataImportPlugin();
        $this->assertSame(
            CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_ROLE,
            $this->getCompanyRoleDataImportPlugin()->getImportType()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRoleDataImportPlugin
     */
    protected function getCompanyRoleDataImportPlugin(): CompanyRoleDataImportPlugin
    {
        return new CompanyRoleDataImportPlugin();
    }
}
