<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentProductSetDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\ContentProductSetDataImport\Communication\Plugin\ContentProductSetDataImportPlugin;
use Spryker\Zed\ContentProductSetDataImport\ContentProductSetDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ContentProductSetDataImport
 * @group Communication
 * @group Plugin
 * @group ContentProductSetDataImportPluginTest
 * Add your own group annotations below this line
 */
class ContentProductSetDataImportPluginTest extends Unit
{
    protected const ERROR_MESSAGE_PRODUCT_SET_WRONG_KEY = 'Please check "product_set_key.default" in the row with key: "APL-1". The wrong product set key passed.';
    protected const KEY_ID_PRODUCT_SET = 'id_product_set';

    /**
     * @var \SprykerTest\Zed\ContentProductSetDataImport\ContentProductSetDataImportCommunicationTester
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
    public function testImportProductSetData(): void
    {
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_set.csv'
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductSetDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData('APL-1');
        $this->tester->assertDatabaseTableContainsData('APL-2');
    }

    /**
     * @return void
     */
    public function testImportProductSetDataWrongKey(): void
    {
        $this->expectExceptionObject(new DataImportException(static::ERROR_MESSAGE_PRODUCT_SET_WRONG_KEY));

        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_set_wrong_key.csv'
        )->setThrowException(true);

        // Act
        $dataImporterReportTransfer = (new ContentProductSetDataImportPlugin())->import($dataImportConfigurationTransfer);

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
        $contentProductSetDataImportPlugin = new ContentProductSetDataImportPlugin();

        // Assert
        $this->assertSame(ContentProductSetDataImportConfig::IMPORT_TYPE_CONTENT_PRODUCT_SET, $contentProductSetDataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    public function testUpdateLocale(): void
    {
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_set(update).csv'
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductSetDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasSetId(66, $this->createUtilEncodingService()->encodeJson([static::KEY_ID_PRODUCT_SET => 1]));
        $this->tester->assertContentLocalizedHasSetId(46, $this->createUtilEncodingService()->encodeJson([static::KEY_ID_PRODUCT_SET => 1]));
    }

    /**
     * @return void
     */
    public function testUpdateLocaleFromDefault(): void
    {
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_set(update_locale_from_default).csv'
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductSetDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasSetId(66, $this->createUtilEncodingService()->encodeJson([static::KEY_ID_PRODUCT_SET => 2]));
        $this->tester->assertContentLocalizedHasSetId(46, $this->createUtilEncodingService()->encodeJson([static::KEY_ID_PRODUCT_SET => 2]));
    }

    /**
     * @return void
     */
    public function testUpdateLocaleToDefault(): void
    {
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_set(update_locale_to_default).csv'
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductSetDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasSetId(66, $this->createUtilEncodingService()->encodeJson([static::KEY_ID_PRODUCT_SET => 1]));
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
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected function createUtilEncodingService(): UtilEncodingServiceInterface
    {
        return new UtilEncodingService();
    }
}
