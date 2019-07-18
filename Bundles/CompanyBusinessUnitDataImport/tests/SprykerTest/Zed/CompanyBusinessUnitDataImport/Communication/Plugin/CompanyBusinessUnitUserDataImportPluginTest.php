<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitDataImport\Communication\Plugin;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
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
 * @group CompanyBusinessUnitUserDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitUserDataImportPluginTest extends AbstractCompanyBusinessUnitDataImportUnitTest
{
    protected const COMPANY_KEY = 'spryker';
    protected const COMPANY_USER_KEY = 'ComUser--1';
    protected const COMPANY_BUSINESS_UNIT_KEY = 'Test_HQ';

    protected const IMPORT_COMPANY_BUSINESS_UNIT_USER_CSV = 'import/company_business_unit_user.csv';
    protected const IMPORT_COMPANY_BUSINESS_UNIT_USER_WITH_INVALID_COMPANY_USER_CSV = 'import/company_business_unit_user_with_invalid_company_user.csv';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportCommunicationTester
     */
    protected $tester;

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
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(static::IMPORT_COMPANY_BUSINESS_UNIT_USER_CSV);

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
}
