<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\BusinessOnBehalfDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportConfig;
use Spryker\Zed\BusinessOnBehalfDataImport\Communication\Plugin\DataImport\BusinessOnBehalfCompanyUserDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group BusinessOnBehalfDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group BusinessOnBehalfCompanyUserDataImportPluginTest
 * Add your own group annotations below this line
 *
 * @group DataImport
 * @group CompanyUser
 */
class BusinessOnBehalfCompanyUserDataImportPluginTest extends Unit
{
    protected const COMPANY_KEY = 'test-company';
    protected const BUSINESS_UNIT_KEY = 'test-business-unit';

    /**
     * @var \SprykerTest\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->prepareTestData();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureCompanyUserDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_user.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $businessOnBehalfCompanyUserDataImportPlugin = new BusinessOnBehalfCompanyUserDataImportPlugin();
        $dataImporterReportTransfer = $businessOnBehalfCompanyUserDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
        $this->tester->assertDatabaseTableContainsCorrectData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $businessOnBehalfCompanyUserDataImportPlugin = new BusinessOnBehalfCompanyUserDataImportPlugin();
        $this->assertSame(BusinessOnBehalfDataImportConfig::IMPORT_TYPE_COMPANY_USER, $businessOnBehalfCompanyUserDataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    protected function prepareTestData(): void
    {
        $this->tester->prepareTestData();
        $companyTransfer = $this->tester->haveCompany(['key' => static::COMPANY_KEY]);
        $this->tester->haveCompanyBusinessUnit([
            'key' => static::BUSINESS_UNIT_KEY,
            'fkCompany' => $companyTransfer->getIdCompany(),
        ]);
    }
}
