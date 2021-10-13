<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CmsSlotDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CmsSlotDataImport\Communication\Plugin\CmsSlotTemplateDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotDataImport
 * @group Communication
 * @group Plugin
 * @group CmsSlotTemplateDataImportPluginTest
 * Add your own group annotations below this line
 */
class CmsSlotTemplateDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsSlotDataImport\CmsSlotDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCmsSlotTemplateImportPopulatesTable(): void
    {
        $this->tester->ensureSpyCmsSlotTemplateTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/cms_slot_template.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImporterReportTransfer = (new CmsSlotTemplateDataImportPlugin())->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertSpyCmsSlotTemplateTableContainsData();
    }

    /**
     * @return void
     */
    public function testCmsSlotTemplateImportWithInvalidDataThrowsException(): void
    {
        // Arrange
        $this->tester->ensureSpyCmsSlotTemplateTableIsEmpty();
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/cms_slot_template_invalid.csv');
        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);

        // Act
        (new CmsSlotTemplateDataImportPlugin())->import($dataImportConfigurationTransfer);
    }
}
