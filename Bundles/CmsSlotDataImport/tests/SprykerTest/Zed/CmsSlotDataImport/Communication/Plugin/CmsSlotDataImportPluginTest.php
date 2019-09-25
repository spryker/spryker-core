<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CmsSlotDataImport\Communication\Plugin\CmsSlotDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotDataImport
 * @group Communication
 * @group Plugin
 * @group CmsSlotDataImportPluginTest
 * Add your own group annotations below this line
 */
class CmsSlotDataImportPluginTest extends Unit
{
    protected const CMS_SLOT_TEMPLATE_PATH = '@CatalogPage/views/catalog/search.twig';
    protected const CMS_SLOT_TEMPLATE_NAME = 'Catalog Search';
    protected const CMS_SLOT_TEMPLATE_DESCRIPTION = 'When you do search by catalog you see this template.';

    /**
     * @var \SprykerTest\Zed\CmsSlotDataImport\CmsSlotDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCmsSlotImportPopulatesTable(): void
    {
        $this->tester->hasCmsSlotTemplate([
            CmsSlotTemplateTransfer::PATH => static::CMS_SLOT_TEMPLATE_PATH,
            CmsSlotTemplateTransfer::NAME => static::CMS_SLOT_TEMPLATE_NAME,
            CmsSlotTemplateTransfer::DESCRIPTION => static::CMS_SLOT_TEMPLATE_DESCRIPTION,
        ]);
        $this->tester->ensureSpyCmsSlotTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/cms_slot.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImporterReportTransfer = (new CmsSlotDataImportPlugin())->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertSpyCmsSlotTableContainsData();
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testCmsSlotImportWithInvalidDataThrowsException(): void
    {
        $this->tester->hasCmsSlotTemplate([
            CmsSlotTemplateTransfer::PATH => static::CMS_SLOT_TEMPLATE_PATH,
            CmsSlotTemplateTransfer::NAME => static::CMS_SLOT_TEMPLATE_NAME,
            CmsSlotTemplateTransfer::DESCRIPTION => static::CMS_SLOT_TEMPLATE_DESCRIPTION,
        ]);
        $this->tester->ensureSpyCmsSlotTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/cms_slot_invalid.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $dataImporterReportTransfer = (new CmsSlotDataImportPlugin())->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
    }
}
