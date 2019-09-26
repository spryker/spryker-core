<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentBannerDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ContentBannerDataImport\Communication\Plugin\ContentBannerDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ContentBannerDataImport
 * @group Communication
 * @group Plugin
 * @group ContentBannerDataImportPluginTest
 * Add your own group annotations below this line
 */
class ContentBannerDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ContentBannerDataImport\ContentBannerDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImporterPopulatesTables(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_banner.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = (new ContentBannerDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testImportWithInvalidDefaultLocaleDataThrowsException(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_banner_invalid(missing_values_for_default_locale).csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImporterReportTransfer = (new ContentBannerDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @expectedException \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function testImportWithMissingPropertyThrowsException(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_banner_invalid(missing_property).csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImporterReportTransfer = (new ContentBannerDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testUpdateLocale(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_banner(update_locale).csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImporterReportTransfer = (new ContentBannerDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedParameterHasValue(66, 'alt_text', 'us banner image 1');
        $this->tester->assertContentLocalizedParameterHasValue(46, 'alt_text', 'updated banner image 1');
    }

    /**
     * @return void
     */
    public function testUpdateLocaleFromDefault(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_banner(update_locale_from_default).csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImporterReportTransfer = (new ContentBannerDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedParameterHasValue(66, 'alt_text', 'us banner image 1');
        $this->tester->assertContentLocalizedParameterHasValue(46, 'alt_text', 'de banner image 1');
    }

    /**
     * @return void
     */
    public function testUpdateLocaleToDefault(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_banner(update_locale_to_default).csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Act
        $dataImporterReportTransfer = (new ContentBannerDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedParameterHasValue(66, 'alt_text', 'us banner image 1');
        $this->tester->assertContentLocalizedDoesNotExist(46);
    }
}
