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
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\ContentProductDataImport\Communication\Plugin\ContentProductAbstractListDataImportPlugin;
use Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
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
    protected const EXCEPTION_ERROR_MESSAGE = 'Found invalid skus in a row with the provided key: "apl1", column: "skus.default"';

    /**
     * @var \SprykerTest\Zed\ContentProductDataImport\ContentProductDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();
        $this->tester->ensureDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportProductAbstractListsData(): void
    {
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list.csv'
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData('apl1');
        $this->tester->assertDatabaseTableContainsData('apl2');
        $this->tester->assertDatabaseTableContainsData('apl3');
    }

    /**
     * @return void
     */
    public function testImportProductAbstractListsDataWrongSkus(): void
    {
        $this->expectExceptionObject(new DataImportException(static::EXCEPTION_ERROR_MESSAGE));

        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list_wrong_skus.csv'
        )->setThrowException(true);

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
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
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list(update).csv'
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(66, $this->createUtilEncodingService()->encodeJson([152, 151]));
        $this->tester->assertContentLocalizedHasProducts(46, $this->createUtilEncodingService()->encodeJson([152, 151]));
    }

    /**
     * @return void
     */
    public function testUpdateLocaleFromDefault(): void
    {
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list(update_locale_from_default).csv'
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(66, $this->createUtilEncodingService()->encodeJson([152, 151]));
        $this->tester->assertContentLocalizedHasProducts(46, $this->createUtilEncodingService()->encodeJson([152, 151]));
    }

    /**
     * @return void
     */
    public function testUpdateLocaleToDefault(): void
    {
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list(update_locale_to_default).csv'
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(66, $this->createUtilEncodingService()->encodeJson([152, 151]));
        $this->tester->assertContentLocalizedDoesNotExist(46);
    }

    /**
     * @param string $importFilePath
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function createConfigurationTransfer(string $importFilePath): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $importFilePath);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();

        return $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    protected function createUtilEncodingService()
    {
        return new UtilEncodingService();
    }
}
