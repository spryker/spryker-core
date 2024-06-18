<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContextDataImport\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\StoreContextDataImport\Communication\Plugin\DataImport\StoreContextDataImportPlugin;
use Spryker\Zed\StoreContextDataImport\StoreContextDataImportConfig;
use SprykerTest\Zed\StoreContextDataImport\StoreContextDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreContextDataImport
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group StoreContextDataImportPluginTest
 * Add your own group annotations below this line
 */
class StoreContextDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\StoreContextDataImport\StoreContextDataImportCommunicationTester
     */
    protected StoreContextDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    public function testStoreContextImportImportsData(): void
    {
        // Arrange
        $this->tester->ensureStoreContextDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/store_context.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $storeContextDataImportPlugin = new StoreContextDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $storeContextDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertGreaterThan(0, $this->tester->getStoreContextCount());
    }

    /**
     * @return void
     */
    public function testImportWithUnknownStore(): void
    {
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Store not found: YY');

        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/store_context_with_unknown_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $storeContextDataImportPlugin = new StoreContextDataImportPlugin();

        // Act
        $storeContextDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testStoreContextGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $storeContextDataImportPlugin = new StoreContextDataImportPlugin();

        // Act
        $importType = $storeContextDataImportPlugin->getImportType();

        // Assert
        $this->assertSame(StoreContextDataImportConfig::IMPORT_TYPE_STORE_CONTEXT, $importType);
    }
}
