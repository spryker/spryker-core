<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserInvitationDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyUserInvitationDataImport\Communication\Plugin\CompanyUserInvitationStatusDataImportPlugin;
use Spryker\Zed\CompanyUserInvitationDataImport\CompanyUserInvitationDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUserInvitationDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyUserInvitationStatusDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyUserInvitationStatusDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUserInvitationDataImport\CompanyUserInvitationDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompanyUserInvitationStatus(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_user_invitation_status.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $companyUserInvitationStatusDataImportPlugin = new CompanyUserInvitationStatusDataImportPlugin();
        $dataImporterReportTransfer = $companyUserInvitationStatusDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyUserInvitationStatusDataImportPlugin = new CompanyUserInvitationStatusDataImportPlugin();
        $this->assertSame(CompanyUserInvitationDataImportConfig::IMPORT_TYPE_COMPANY_USER_INVITATION_STATUS, $companyUserInvitationStatusDataImportPlugin->getImportType());
    }
}
