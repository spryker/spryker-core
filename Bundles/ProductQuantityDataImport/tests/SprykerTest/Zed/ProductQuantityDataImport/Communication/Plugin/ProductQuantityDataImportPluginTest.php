<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantityDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\ProductQuantityDataImport\Communication\Plugin\ProductQuantityDataImportPlugin;
use Spryker\Zed\ProductQuantityDataImport\ProductQuantityDataImportConfig;

class ProductQuantityDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductQuantityDataImport\ProductQuantityDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_quantity.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productQuantityDataImportPlugin = new ProductQuantityDataImportPlugin();
        $dataImporterReportTransfer = $productQuantityDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productQuantityDataImportPlugin = new ProductQuantityDataImportPlugin();
        $this->assertSame(
            ProductQuantityDataImportConfig::IMPORT_TYPE_PRODUCT_QUANTITY,
            $productQuantityDataImportPlugin->getImportType()
        );
    }
}
