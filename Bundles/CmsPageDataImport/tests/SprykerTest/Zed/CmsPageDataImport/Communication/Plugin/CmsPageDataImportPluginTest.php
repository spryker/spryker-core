<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CmsPageDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CmsPageDataImport\CmsPageDataImportConfig;
use Spryker\Zed\CmsPageDataImport\Communication\Plugin\CmsPageDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsPageDataImport
 * @group Communication
 * @group Plugin
 * @group CmsPageDataImportPluginTest
 * Add your own group annotations below this line
 */
class CmsPageDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsPageDataImport\CmsPageDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCmsPage(): void
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/cms_page.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $cmsPageDataImportPlugin = new CmsPageDataImportPlugin();
        $dataImporterReportTransfer = $cmsPageDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableCmsPageContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $cmsPageDataImportPlugin = new CmsPageDataImportPlugin();
        $this->assertSame(CmsPageDataImportConfig::IMPORT_TYPE_CMS_PAGE, $cmsPageDataImportPlugin->getImportType());
    }
}
