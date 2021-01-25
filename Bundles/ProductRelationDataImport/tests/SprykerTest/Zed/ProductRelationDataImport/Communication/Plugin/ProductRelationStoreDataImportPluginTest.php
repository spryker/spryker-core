<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductRelationDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductRelationDataImport\Communication\Plugin\ProductRelationStoreDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelationDataImport
 * @group Communication
 * @group Plugin
 * @group ProductRelationStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductRelationStoreDataImportPluginTest extends Unit
{
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var \SprykerTest\Zed\ProductRelationDataImport\ProductRelationDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsProductRelations(): void
    {
        //Arrange
        $this->tester->ensureProductRelationStoreTableIsEmpty();
        $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $this->tester->haveStore([
            StoreTransfer::NAME => 'AT',
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_relation_store.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productRelationDataImportPlugin = new ProductRelationStoreDataImportPlugin();

        //Act
        $dataImporterReportTransfer = $productRelationDataImportPlugin->import($dataImportConfigurationTransfer);

        //Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess(), 'Data import should finish successfully');

        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of product relation stores is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }
}
