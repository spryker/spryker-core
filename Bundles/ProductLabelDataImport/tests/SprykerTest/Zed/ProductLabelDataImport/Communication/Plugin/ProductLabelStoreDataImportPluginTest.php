<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\ProductLabelDataImport\tests\SprykerTest\Zed\ProductLabelDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductLabelDataImport\Communication\Plugin\ProductLabelStoreDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group ProductLabelDataImport
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelDataImport
 * @group Communication
 * @group Plugin
 * @group ProductLabelStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductLabelStoreDataImportPluginTest extends Unit
{
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var \SprykerTest\Zed\ProductLabelDataImport\ProductLabelDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsProductLabelStore(): void
    {
        //Arrange
        $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $this->tester->haveStore([StoreTransfer::NAME => 'AT']);

        $this->tester->haveProductLabel([ProductLabelTransfer::NAME => 'TEST']);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_label_store.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productRelationDataImportPlugin = new ProductLabelStoreDataImportPlugin();

        //Act
        $dataImporterReportTransfer = $productRelationDataImportPlugin->import($dataImportConfigurationTransfer);

        //Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess(), 'Data import should finish successfully');

        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of product relations is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }
}
