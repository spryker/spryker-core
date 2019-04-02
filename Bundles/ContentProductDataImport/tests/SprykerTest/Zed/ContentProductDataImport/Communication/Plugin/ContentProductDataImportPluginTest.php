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
    protected const EXCEPTION_ERROR_MESSAGE = 'Found not valid skus in the row with key: "apl1", column: "skus.default"';

    /**
     * @var \SprykerTest\Zed\ContentProductDataImport\ContentProductDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportProductAbstractListsData(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_product_abstract_list.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testImportProductAbstractListsDataWrongSkus(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->expectExceptionObject(new DataImportException(static::EXCEPTION_ERROR_MESSAGE));

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_product_abstract_list_wrong_skus.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $contentProductAbstractListDataImportPlugin = new ContentProductAbstractListDataImportPlugin();

        // Assert
        $this->assertSame(ContentProductDataImportConfig::IMPORT_TYPE_CONTENT_PRODUCT, $contentProductAbstractListDataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    public function testUpdateLocale(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_product_abstract_list(update).csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(66, [152, 151]);
        $this->tester->assertContentLocalizedHasProducts(46, [152, 151]);
    }

    /**
     * @return void
     */
    public function testUpdateLocaleFromDefault(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_product_abstract_list(update_locale_from_default).csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(66, [152, 151]);
        $this->tester->assertContentLocalizedHasProducts(46, [152, 151]);
    }

    /**
     * @return void
     */
    public function testUpdateLocaleToDefault(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_product_abstract_list(update_locale_to_default).csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(66, [152, 151]);
        $this->tester->assertContentLocalizedDoesNotExist(46);
    }
}
