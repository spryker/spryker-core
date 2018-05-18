<?php


namespace SprykerTest\Zed\BusinessOnBehalfDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportConfig;
use Spryker\Zed\BusinessOnBehalfDataImport\Communication\Plugin\BusinessOnBehalfDataImportPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group BusinessOnBehalfDataImport
 * @group Communication
 * @group Plugin
 * @group BusinessOnBehalfDataImportPluginTest
 * Add your own group annotations below this line
 */
class BusinessOnBehalfDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $this->tester->prepareTestData();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/company_user.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $businessOnBehalfDataImportPlugin = new BusinessOnBehalfDataImportPlugin();
        $dataImporterReportTransfer = $businessOnBehalfDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $businessOnBehalfDataImportPlugin = new BusinessOnBehalfDataImportPlugin();
        $this->assertSame(BusinessOnBehalfDataImportConfig::IMPORT_TYPE_COMPANY_USER, $businessOnBehalfDataImportPlugin->getImportType());
    }
}
