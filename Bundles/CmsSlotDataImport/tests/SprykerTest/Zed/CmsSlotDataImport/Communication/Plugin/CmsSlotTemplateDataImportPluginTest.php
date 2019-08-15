<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CmsSlotDataImport\Communication\Plugin\CmsSlotTemplateDataImportPlugin;

/**
 * Auto-generated group annotations
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
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testCmsSlotTemplateImportWithInvalidDataThrowsException(): void
    {
        $this->tester->ensureSpyCmsSlotTemplateTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/cms_slot_template_invalid.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $dataImporterReportTransfer = (new CmsSlotTemplateDataImportPlugin())->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
    }
}
