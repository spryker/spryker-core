<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUnitAddressDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyUnitAddressDataImport\Communication\Plugin\CompanyUnitAddressDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressDataImport\CompanyUnitAddressDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddressDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyUnitAddressDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyUnitAddressDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddressDataImport\CompanyUnitAddressDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompany(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/company_unit_address.csv');

        $companyUnitAddressDataImportPlugin = new CompanyUnitAddressDataImportPlugin();
        $dataImporterReportTransfer = $companyUnitAddressDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyNotFound(): void
    {
        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/company_unit_address_with_invalid_company.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyUnitAddressDataImportPlugin = new CompanyUnitAddressDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find company by key "invalid-company-key"');

        $companyUnitAddressDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCountryNotFound(): void
    {
        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/company_unit_address_with_invalid_country.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyUnitAddressDataImportPlugin = new CompanyUnitAddressDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find a country by iso2_code "XX" or iso3_code "YYY');

        $companyUnitAddressDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $CompanyUnitAddressDataImportPlugin = new CompanyUnitAddressDataImportPlugin();
        $this->assertSame(CompanyUnitAddressDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS, $CompanyUnitAddressDataImportPlugin->getImportType());
    }

    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function getDataImporterReaderConfigurationTransfer(string $filePath): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $filePath);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        return $dataImportConfigurationTransfer;
    }
}
