<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentNavigationDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\ContentNavigationDataImport\Communication\Plugin\DataImport\ContentNavigationDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ContentNavigationDataImport
 * @group Communication
 * @group Plugin
 * @group ContentNavigationDataImportPluginTest
 * Add your own group annotations below this line
 */
class ContentNavigationDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ContentNavigationDataImport\ContentNavigationDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImporterPopulatesTables(): void
    {
        // Arrange
        $this->tester->haveNavigation([NavigationTransfer::KEY => 'content_navigation']);
        $contentNavigationDataImportPlugin = new ContentNavigationDataImportPlugin();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_navigation.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $contentNavigationDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testImportWithEmptyDefaultLocaleDataReturnsFalse(): void
    {
        // Arrange
        $this->tester->haveNavigation([NavigationTransfer::KEY => 'content_navigation_without_default_locale']);
        $contentNavigationDataImportPlugin = new ContentNavigationDataImportPlugin();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_navigation_without_default_locale.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $contentNavigationDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testImportWithInvalidNavigationKeyReturnsFalse(): void
    {
        // Arrange
        $contentNavigationDataImportPlugin = new ContentNavigationDataImportPlugin();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_navigation_with_invalid_navigation_key.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $dataImporterReportTransfer = $contentNavigationDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
    }
}
