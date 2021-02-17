<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use ReflectionClass;
use Spryker\Zed\CompanyDataImport\Communication\Plugin\CompanyDataImportPlugin;
use Spryker\Zed\CompanyDataImport\CompanyDataImportConfig;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyDataImport\CompanyDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompany(): void
    {
        $this->tester->truncateCompanyRelations();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $dataImportConfigurationTransfer->setThrowException(true);

        $companyDataImportPlugin = new CompanyDataImportPlugin();

        $pluginReflection = new ReflectionClass($companyDataImportPlugin);

        $facadePropertyReflection = $pluginReflection->getParentClass()->getProperty('facade');
        $facadePropertyReflection->setAccessible(true);
        $this->tester->mockFactoryMethod('createTransactionAwareDataSetStepBroker', new DataSetStepBroker());
        $facadePropertyReflection->setValue($companyDataImportPlugin, $this->tester->getFacade());

        $dataImporterReportTransfer = $companyDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $companyDataImportPlugin = new CompanyDataImportPlugin();
        $this->assertSame(CompanyDataImportConfig::IMPORT_TYPE_COMPANY, $companyDataImportPlugin->getImportType());
    }
}
