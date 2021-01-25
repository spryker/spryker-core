<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductLabelDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductLabelDataImport\Communication\Plugin\ProductLabelDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelDataImport
 * @group Communication
 * @group Plugin
 * @group ProductLabelDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductLabelDataImportPluginTest extends Unit
{
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var \SprykerTest\Zed\ProductLabelDataImport\ProductLabelDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsProductLabel(): void
    {
        //Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::SKU => '0001',
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::SKU => '0002',
        ]);

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/product_label.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $productRelationDataImportPlugin = new ProductLabelDataImportPlugin();

        //Act
        $dataImporterReportTransfer = $productRelationDataImportPlugin->import($dataImportConfigurationTransfer);
        $this->tester->removeProductLabelProductAbstractRelationsByProductAbstractTransfer($productAbstractTransfer1);
        $this->tester->removeProductLabelProductAbstractRelationsByProductAbstractTransfer($productAbstractTransfer2);

        //Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess(), 'Data import should finish successfully');

        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of product labels is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }
}
