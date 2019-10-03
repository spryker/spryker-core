<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StockDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StockDataImport\Communication\Plugin\StockStoreDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StockDataImport
 * @group Communication
 * @group Plugin
 * @group StockStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class StockStoreDataImportPluginTest extends Unit
{
    public const EXPECTED_IMPORT_COUNT = 4;

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
        $this->tester->ensureStockStoreTableIsEmpty();
        $this->tester->haveStock([
            StockTransfer::NAME => 'Warehouse1',
        ]);
        $this->tester->haveStock([
            StockTransfer::NAME => 'Warehouse2',
        ]);
        $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $this->tester->haveStore([
            StoreTransfer::NAME => 'AT',
        ]);
        $this->tester->haveStore([
            StoreTransfer::NAME => 'US',
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/warehouse_store.csv');
        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $stockDataImportPlugin = new StockStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $stockDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->tester->assertStockStoreTableContainsData();
        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of stock stores is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }
}
