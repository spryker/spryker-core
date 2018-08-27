<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitUserDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyBusinessUnitDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitDataImportPluginTest extends Unit
{
    protected const COMPANY_KEY = 'spryker';
    protected const COMPANY_USER_KEY = 'ComUser--1';
    protected const COMPANY_BUSINESS_UNIT_KEY = 'Test_HQ';

    protected const IMPORT_COMPANY_BUSINESS_UNIT_CSV = 'import/company_business_unit.csv';
    protected const IMPORT_COMPANY_BUSINESS_UNIT_WITH_INVALID_COMPANY_CSV = 'import/company_business_unit_with_invalid_company.csv';
    protected const IMPORT_COMPANY_BUSINESS_UNIT_WITH_INVALID_PARENT_CSV = 'import/company_business_unit_with_invalid_parent.csv';

    protected const IMPORT_COMPANY_BUSINESS_UNIT_USER_CSV = 'import/company_business_unit_user.csv';
    protected const IMPORT_COMPANY_BUSINESS_UNIT_USER_WITH_INVALID_COMPANY_USER_CSV = 'import/company_business_unit_user_with_invalid_company_user.csv';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompanyBusinessUnit(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->tester->haveCompany([CompanyTransfer::KEY => static::COMPANY_KEY]);

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(
            static::IMPORT_COMPANY_BUSINESS_UNIT_CSV
        );
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();
        $dataImporterReportTransfer = $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     * @expectedExceptionMessage Could not find company by key "invalid company"
     *
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(
            static::IMPORT_COMPANY_BUSINESS_UNIT_WITH_INVALID_COMPANY_CSV
        );
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();

        $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     * @expectedExceptionMessage Could not find company business unit by key "invalid parent"
     *
     * @return void
     */
    public function testImportThrowsExceptionWhenParentBusinessUnitNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->tester->haveActiveCompany([CompanyTransfer::KEY => static::COMPANY_KEY]);
        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(
            static::IMPORT_COMPANY_BUSINESS_UNIT_WITH_INVALID_PARENT_CSV
        );
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();
        $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportCompanyBusinessUnitUser(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::KEY => static::COMPANY_KEY,
        ]);

        $this->tester->haveCompanyUser([
            CompanyUserTransfer::KEY => static::COMPANY_USER_KEY,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
        ]);

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::KEY => static::COMPANY_BUSINESS_UNIT_KEY,
        ]);

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(static::IMPORT_COMPANY_BUSINESS_UNIT_USER_CSV);
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyBusinessUnitUserDataImportPlugin = new CompanyBusinessUnitUserDataImportPlugin();
        $companyBusinessUnitUserDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testImportCompanyBusinessUnitUserThrowsExceptionWhenCompanyUserKeyNotFound(): void
    {
        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(static::IMPORT_COMPANY_BUSINESS_UNIT_USER_WITH_INVALID_COMPANY_USER_CSV);
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyBusinessUnitUserDataImportPlugin = new CompanyBusinessUnitUserDataImportPlugin();
        $companyBusinessUnitUserDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();
        $this->assertSame(
            CompanyBusinessUnitDataImportConfig::IMPORT_TYPE_COMPANY_BUSINESS_UNIT,
            $companyBusinessUnitDataImportPlugin->getImportType()
        );

        $companyBusinessUnitUserDataImportPlugin = new CompanyBusinessUnitUserDataImportPlugin();
        $this->assertSame(
            CompanyBusinessUnitDataImportConfig::IMPORT_TYPE_COMPANY_BUSINESS_UNIT_USER,
            $companyBusinessUnitUserDataImportPlugin->getImportType()
        );
    }

    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function getDataImportConfigurationTransfer(string $filePath): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $filePath);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        return $dataImportConfigurationTransfer;
    }
}
