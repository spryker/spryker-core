<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRolePermissionDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\CompanyRoleDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyRoleDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group CompanyRolePermissionDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyRolePermissionDataImportPluginTest extends AbstractCompanyRoleDataImportMock
{
    /**
     * @return void
     */
    public function testImportCompanyRolePermissionData(): void
    {
        $this->tester->truncateCompanyToPermissionRoles();
        $this->tester->assertCompanyRoleToPermissionTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/company_role_permission.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $dataImporterReportTransfer = $this->getCompanyRolePermissionDataImportPlugin()->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyRoleToPermissionTableHasRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $this->assertSame(
            CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_ROLE_PERMISSION,
            $this->getCompanyRolePermissionDataImportPlugin()->getImportType()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRolePermissionDataImportPlugin
     */
    protected function getCompanyRolePermissionDataImportPlugin(): CompanyRolePermissionDataImportPlugin
    {
        return new CompanyRolePermissionDataImportPlugin();
    }
}
