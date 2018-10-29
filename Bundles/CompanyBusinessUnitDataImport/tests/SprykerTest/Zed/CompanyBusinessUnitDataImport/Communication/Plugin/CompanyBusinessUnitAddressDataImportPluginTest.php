<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitDataImport\Communication\Plugin;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitAddressDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyBusinessUnitAddressDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitAddressDataImportPluginTest extends AbstractCompanyBusinessUnitDataImportUnitTest
{
    protected const COMPANY_ADDRESS_KEY = 'Address--1';
    protected const COMPANY_BUSINESS_UNIT_KEY = 'Test_HQ';

    protected const IMPORT_COMPANY_BUSINESS_UNIT_ADDRESS_CSV = 'import/company_business_unit_address.csv';
    protected const IMPORT_COMPANY_BUSINESS_UNIT_ADDRESS_WITH_INVALID_COMPANY_ADDRESS_CSV = 'import/company_business_unit_address_with_invalid_company_address.csv';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportCompanyBusinessUnitAddress(): void
    {
        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::KEY => static::COMPANY_ADDRESS_KEY,
        ]);

        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::KEY => static::COMPANY_BUSINESS_UNIT_KEY,
        ]);

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(static::IMPORT_COMPANY_BUSINESS_UNIT_ADDRESS_CSV);

        $companyBusinessUnitAddressDataImportPlugin = new CompanyBusinessUnitAddressDataImportPlugin();
        $companyBusinessUnitAddressDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testImportCompanyBusinessUnitAddressThrowsExceptionWhenCompanyAddressKeyNotFound(): void
    {
        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(static::IMPORT_COMPANY_BUSINESS_UNIT_ADDRESS_WITH_INVALID_COMPANY_ADDRESS_CSV);

        $companyBusinessUnitAddressDataImportPlugin = new CompanyBusinessUnitAddressDataImportPlugin();
        $companyBusinessUnitAddressDataImportPlugin->import($dataImportConfigurationTransfer);
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

        $companyBusinessUnitAddressDataImportPlugin = new CompanyBusinessUnitAddressDataImportPlugin();
        $this->assertSame(
            CompanyBusinessUnitDataImportConfig::IMPORT_TYPE_COMPANY_BUSINESS_UNIT_ADDRESS,
            $companyBusinessUnitAddressDataImportPlugin->getImportType()
        );
    }
}
