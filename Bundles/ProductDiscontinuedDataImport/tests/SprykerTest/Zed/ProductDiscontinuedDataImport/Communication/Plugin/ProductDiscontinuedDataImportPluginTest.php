<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\ProductDiscontinuedDataImport\Communication\Plugin\ProductDiscontinuedDataImportPlugin;
use Spryker\Zed\ProductDiscontinuedDataImport\ProductDiscontinuedDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscontinuedDataImport
 * @group Communication
 * @group Plugin
 * @group ProductDiscontinuedDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductDiscontinuedDataImportPluginTest extends Unit
{
    protected const DISCONTINUED_PRODUCT_TEST_SKU = 'discontinued_sku';

    /**
     * @var \SprykerTest\Zed\ProductDiscontinuedDataImport\ProductDiscontinuedDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => static::DISCONTINUED_PRODUCT_TEST_SKU,
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_discontinued.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productDiscontinuedDataImportPlugin = new ProductDiscontinuedDataImportPlugin();
        $dataImporterReportTransfer = $productDiscontinuedDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_discontinued_product_not_exists.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $productDiscontinuedDataImportPlugin = new ProductDiscontinuedDataImportPlugin();

        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find product by sku invalid_discontinued_sku');

        $productDiscontinuedDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $productDiscontinuedDataImportPlugin = new ProductDiscontinuedDataImportPlugin();
        $this->assertSame(ProductDiscontinuedDataImportConfig::IMPORT_TYPE_PRODUCT_DISCONTINUED, $productDiscontinuedDataImportPlugin->getImportType());
    }
}
