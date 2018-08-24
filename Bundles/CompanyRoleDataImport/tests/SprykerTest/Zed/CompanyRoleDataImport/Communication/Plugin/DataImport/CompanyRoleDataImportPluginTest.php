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
use Generated\Shared\Transfer\SpyCompanyUserEntityTransfer;
use Spryker\Client\CompanyRole\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRoleDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRolePermissionDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyUserRoleDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\CompanyRoleDataImportConfig;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use SprykerTest\Zed\CompanyRoleDataImport\MockPermissionPlugin;

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
    protected const COMPANY_USER_KEY = 'ComUser--1';
    protected const PERMISSION_PLUGINS = [
        MockPermissionPlugin::class,
    ];

    /**
     * @var \SprykerTest\Zed\CompanyRoleDataImport\CompanyRoleDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new PermissionStoragePlugin(),
        ]);

        $this->prepareTestData();
    }

    /**
     * @return void
     */
    public function testImportCompanyRoleData(): void
    {
        $this->tester->truncateCompanyRoles();
        $this->tester->assertCompanyRoleTableIsEmtpy();

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
    public function testImportCompanyRolePermissionData(): void
    {
        $this->tester->truncateCompanyToPermissionRoles();
        $this->tester->assertCompanyRoleToPermissionTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_role_permission.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new CompanyRolePermissionDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyRoleToPermissionTableHasRecords();
    }

    /**
     * @return void
     */
    public function testImportCompanyUserRoleData(): void
    {
        $this->tester->truncateCompanyToCompanyUserRoles();
        $this->tester->assertCompanyRoleToCompanyUserTableIsEmtpy();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_user_role.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new CompanyUserRoleDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyRoleToCompanyUserTableHasRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyRoleDataImportPlugin = new CompanyRoleDataImportPlugin();
        $this->assertSame(CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_ROLE, $companyRoleDataImportPlugin->getImportType());

        $companyRolePermissionDataImportPlugin = new CompanyRolePermissionDataImportPlugin();
        $this->assertSame(CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_ROLE_PERMISSION, $companyRolePermissionDataImportPlugin->getImportType());

        $companyUserRoleDataImportPlugin = new CompanyUserRoleDataImportPlugin();
        $this->assertSame(CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_USER_ROLE, $companyUserRoleDataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function prepareTestData(): void
    {
        $customerTransfer = $this->tester->haveCustomer();

        $companyTransfer = $this->tester->haveCompany([
            SpyCompanyEntityTransfer::KEY => static::COMPANY_KEY,
        ]);

        $this->tester->haveCompanyUser([
            SpyCompanyUserEntityTransfer::KEY => static::COMPANY_USER_KEY,
            SpyCompanyUserEntityTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            SpyCompanyUserEntityTransfer::CUSTOMER => $customerTransfer,
        ]);

        foreach (static::PERMISSION_PLUGINS as $permissionPlugin) {
            $this->tester->havePermission(new $permissionPlugin);
        }
    }
}
