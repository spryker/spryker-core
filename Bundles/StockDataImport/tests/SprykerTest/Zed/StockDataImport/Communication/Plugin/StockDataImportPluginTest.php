<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StockDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\StockDataImport\Communication\Plugin\StockDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StockDataImport
 * @group Communication
 * @group Plugin
 * @group StockDataImportPluginTest
 * Add your own group annotations below this line
 */
class StockDataImportPluginTest extends Unit
{
    public const EXPECTED_IMPORT_COUNT = 3;

    /**
     * @var \SprykerTest\Zed\StockDataImport\StockDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsStock(): void
    {
        // Arrange
        $this->tester->ensureStockTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/warehouse.csv');
        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $stockDataImportPlugin = new StockDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $stockDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->tester->assertStockTableContainsData();
        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of price product schedules is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }
}
