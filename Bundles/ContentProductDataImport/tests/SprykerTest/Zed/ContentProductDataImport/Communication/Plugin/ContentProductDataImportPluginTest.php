<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentProductDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ContentProductDataImport\Communication\Plugin\ContentProductAbstractListDataImportPlugin;
use Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ContentProductDataImport
 * @group Communication
 * @group Plugin
 * @group ContentProductDataImportPluginTest
 * Add your own group annotations below this line
 */
class ContentProductDataImportPluginTest extends Unit
{
    protected const EXCEPTION_ERROR_MESSAGE = 'Found not valid skus in the row with key:"apl1", column:"skus.default"';

    /**
     * @var \SprykerTest\Zed\ContentProductDataImport\ContentProductDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportProductAbstractListsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_product_abstract_list.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $contentProductAbstractListDataImportPlugin = new ContentProductAbstractListDataImportPlugin();
        $dataImporterReportTransfer = $contentProductAbstractListDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testImportProductAbstractListsDataWrongSkus(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->expectExceptionObject(new DataImportException(static::EXCEPTION_ERROR_MESSAGE));

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_product_abstract_list_wrong_skus.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $contentProductAbstractListDataImportPlugin = new ContentProductAbstractListDataImportPlugin();
        $dataImporterReportTransfer = $contentProductAbstractListDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $contentProductAbstractListDataImportPlugin = new ContentProductAbstractListDataImportPlugin();
        $this->assertSame(ContentProductDataImportConfig::IMPORT_TYPE_CONTENT_PRODUCT, $contentProductAbstractListDataImportPlugin->getImportType());
    }
}
