<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUserDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
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
    protected const COMPANY_KEY = 'Test_ltd';
    protected const COMPANY_BUSINESS_UNIT_KEY_1 = 'Test_HQ';
    protected const COMPANY_BUSINESS_UNIT_KEY_2 = 'Test_Department';

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
        $this->tester->haveCustomer([
            SpyCustomerEntityTransfer::CUSTOMER_REFERENCE => 'DE--8',
        ]);

        $this->tester->haveCustomer([
            SpyCustomerEntityTransfer::CUSTOMER_REFERENCE => 'DE--9',
        ]);

        $companyTransfer = $this->tester->haveCompany([
            SpyCompanyEntityTransfer::KEY => static::COMPANY_KEY,
        ]);

        $this->tester->haveCompanyBusinessUnit([
            SpyCompanyBusinessUnitEntityTransfer::KEY => static::COMPANY_BUSINESS_UNIT_KEY_1,
            SpyCompanyBusinessUnitEntityTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $this->tester->haveCompanyBusinessUnit([
            SpyCompanyBusinessUnitEntityTransfer::KEY => static::COMPANY_BUSINESS_UNIT_KEY_2,
            SpyCompanyBusinessUnitEntityTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
    }
}
