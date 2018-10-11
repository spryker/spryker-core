<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUserDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
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
    protected const CUSTOMER_REFERENCE_1 = 'DE--8';
    protected const CUSTOMER_REFERENCE_2 = 'DE--9';
    protected const COMPANY_KEY = 'Test_ltd';

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

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new CompanyUserDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertCompanyUserTableHasRecords();
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testImportWithInvalidDataThrowsException(): void
    {
        $this->tester->truncateCompanyUsers();
        $this->tester->assertCompanyUserTableIsEmtpy();
        $this->prepareTestData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_user_with_invalid_company.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $dataImportPlugin = new CompanyUserDataImportPlugin();
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());

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
            CustomerTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE_1,
        ]);

        $this->tester->haveCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::CUSTOMER_REFERENCE_2,
        ]);

        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::KEY => static::COMPANY_KEY,
        ]);
    }
}
