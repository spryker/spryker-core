<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\FileManagerDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\FileManagerDataImport\Communication\Plugin\FileManagerDataImportPlugin;
use Spryker\Zed\FileManagerDataImport\FileManagerDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileManagerDataImport
 * @group Communication
 * @group Plugin
 * @group FileManagerDataImportPluginTest
 * Add your own group annotations below this line
 */
class FileManagerDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\FileManagerDataImport\FileManagerDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsMimeTypes(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/mime_type.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $fileManagerDataImportPlugin = new FileManagerDataImportPlugin();
        $dataImporterReportTransfer = $fileManagerDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $fileManagerDataImportPlugin = new FileManagerDataImportPlugin();
        $this->assertSame(FileManagerDataImportConfig::IMPORT_TYPE_MIME_TYPE, $fileManagerDataImportPlugin->getImportType());
    }
}
