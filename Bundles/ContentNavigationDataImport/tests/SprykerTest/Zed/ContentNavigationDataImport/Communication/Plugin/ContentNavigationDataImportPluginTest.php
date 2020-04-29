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
    protected const TEST_NAVIGATION_KEY = 'TEST_NAVIGATION';

    /**
     * @var \SprykerTest\Zed\ContentNavigationDataImport\ContentNavigationDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->haveNavigation([NavigationTransfer::KEY => static::TEST_NAVIGATION_KEY]);
        $this->tester->ensureContentTablesAreEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensureNavigationTableIsEmpty();
        $this->tester->ensureContentTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testImporterPopulatesTables(): void
    {
        // Arrange
        $contentNavigationDataImportPlugin = new ContentNavigationDataImportPlugin();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_navigation.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $contentCountBefore = $this->tester->getContentTableCount();
        $dataImporterReportTransfer = $contentNavigationDataImportPlugin->import($dataImportConfigurationTransfer);
        $contentCountAfter = $this->tester->getContentTableCount();

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());
        $this->assertNotSame($contentCountBefore, $contentCountAfter);
    }

    /**
     * @return void
     */
    public function testImportWithEmptyDefaultLocaleDataReturnsFalse(): void
    {
        // Arrange
        $contentNavigationDataImportPlugin = new ContentNavigationDataImportPlugin();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/content_navigation_without_default_locale.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $contentCountBefore = $this->tester->getContentTableCount();
        $dataImporterReportTransfer = $contentNavigationDataImportPlugin->import($dataImportConfigurationTransfer);
        $contentCountAfter = $this->tester->getContentTableCount();

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame($contentCountAfter, $contentCountBefore);
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
        $contentCountBefore = $this->tester->getContentTableCount();
        $dataImporterReportTransfer = $contentNavigationDataImportPlugin->import($dataImportConfigurationTransfer);
        $contentCountAfter = $this->tester->getContentTableCount();

        // Assert
        $this->assertFalse($dataImporterReportTransfer->getIsSuccess());
        $this->assertSame($contentCountAfter, $contentCountBefore);
    }
}
