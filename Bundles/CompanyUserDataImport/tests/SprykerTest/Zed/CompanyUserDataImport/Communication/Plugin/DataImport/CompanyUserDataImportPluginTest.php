<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUserDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;
use Generated\Shared\Transfer\SpyCompanyEntityTransfer;
use Generated\Shared\Transfer\SpyCustomerEntityTransfer;
use Spryker\Zed\CompanyUserDataImport\Communication\Plugin\DataImport\CompanyUserDataImportPlugin;
use Spryker\Zed\CompanyUserDataImport\CompanyUserDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUserDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group CompanyUserDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyUserDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUserDataImport\CompanyUserDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->truncateCompanyUsers();
        $this->tester->assertCompanyUserTableIsEmtpy();
        $this->prepareTestData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_user.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new CompanyUserDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyUserTableHasRecords();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new CompanyUserDataImportPlugin();
        $this->assertSame(CompanyUserDataImportConfig::IMPORT_TYPE_COMPANY_USER, $dataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function prepareTestData(): void
    {
        $this->tester->haveRegisteredCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => 'REF--1',
        ]);

        $this->tester->haveRegisteredCustomer([
            SpyCustomerEntityTransfer::CUSTOMER_REFERENCE => 'REF--2',
        ]);

        $this->tester->haveCompany([
            SpyCompanyEntityTransfer::KEY => 'Test_ltd',
        ]);

        $this->tester->haveCompanyBusinessUnit([
            SpyCompanyBusinessUnitEntityTransfer::KEY => 'Test_HQ',
        ]);

        $this->tester->haveCompanyBusinessUnit([
            SpyCompanyBusinessUnitEntityTransfer::KEY => 'Test_Department',
        ]);
    }
}
