<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyUserRoleDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\CompanyRoleDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyRoleDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group CompanyUserRoleDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyUserRoleDataImportPluginTest extends AbstractCompanyRoleDataImportMock
{
    /**
     * @return void
     */
    public function testImportCompanyUserRoleData(): void
    {
        $this->tester->truncateCompanyToCompanyUserRoles();
        $this->tester->assertCompanyRoleToCompanyUserTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/company_user_role.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $dataImporterReportTransfer = $this->getCompanyUserRoleDataImportPlugin()->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyRoleToCompanyUserTableHasRecords();
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testImportCompanyUserRoleWithInvlaidCompanyUser(): void
    {
        $this->tester->truncateCompanyToCompanyUserRoles();
        $this->tester->assertCompanyRoleToCompanyUserTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/company_user_role_with_invalid_company_user.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $dataImporterReportTransfer = $this->getCompanyUserRoleDataImportPlugin()->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyRoleToCompanyUserTableHasRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $this->assertSame(
            CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_USER_ROLE,
            $this->getCompanyUserRoleDataImportPlugin()->getImportType()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyUserRoleDataImportPlugin
     */
    protected function getCompanyUserRoleDataImportPlugin(): CompanyUserRoleDataImportPlugin
    {
        return new CompanyUserRoleDataImportPlugin();
    }
}
