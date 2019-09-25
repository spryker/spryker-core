<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabelDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Communication\Plugin\CompanyUnitAddressLabelDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\CompanyUnitAddressLabelDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddressLabelDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyUnitAddressLabelDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyUnitAddressLabelDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddressLabelDataImport\CompanyUnitAddressLabelDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompanyUnitAddressLabel(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_unit_address_label.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $companyUnitAddressLabelDataImportPlugin = new CompanyUnitAddressLabelDataImportPlugin();
        $dataImporterReportTransfer = $companyUnitAddressLabelDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyUnitAddressLabelDataImportPlugin = new CompanyUnitAddressLabelDataImportPlugin();
        $this->assertSame(CompanyUnitAddressLabelDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS_LABEL, $companyUnitAddressLabelDataImportPlugin->getImportType());
    }
}
