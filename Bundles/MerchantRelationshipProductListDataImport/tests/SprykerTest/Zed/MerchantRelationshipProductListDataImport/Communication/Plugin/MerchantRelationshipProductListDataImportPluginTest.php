<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipProductListDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Communication\Plugin\MerchantRelationshipProductListDataImportPlugin;
use Spryker\Zed\MerchantRelationshipProductListDataImport\MerchantRelationshipProductListDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipProductListDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantRelationshipProductListDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipProductListDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipProductListDataImport\MerchantRelationshipProductListDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Assign
        $this->tester->ensureProductListTableIsEmpty();
        $this->tester->haveProductLists();
        $this->tester->createMerchantRelationship('mr-008');

        $merchantRelationshipProductListDataImportPlugin = new MerchantRelationshipProductListDataImportPlugin();
        // Act
        $dataImporterReportTransfer = $merchantRelationshipProductListDataImportPlugin->import(
            $this->getDataImporterReaderConfigurationTransfer('import/merchant_relation_to_product_list.csv')
        );

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertProductListTableContainsRecords();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductListKeyIsNotDefined(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/merchant_relation_to_product_list_without_product_list_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('"product_list_key" is required.');

        //Act
        $merchantRelationshipProductListDataImportPlugin = new MerchantRelationshipProductListDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductListIsNotFound(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/merchant_relation_to_product_list_with_invalid_product_list.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find Product List by key "not-existing-list"');

        //Act
        $merchantRelationshipProductListDataImportPlugin = new MerchantRelationshipProductListDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenMerchantRelationKeyIsNotDefined(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/merchant_relation_to_product_list_without_merchant_relation_key.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('"merchant_relation_key" is required.');

        //Act
        $merchantRelationshipProductListDataImportPlugin = new MerchantRelationshipProductListDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenMerchantRelationIsNotFound(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->getDataImporterReaderConfigurationTransfer('import/merchant_relation_to_product_list_with_invalid_merchant_relation.csv');
        $dataImportConfigurationTransfer->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find Merchant Relationship by key "not-existing-merchant-relation"');

        //Act
        $merchantRelationshipProductListDataImportPlugin = new MerchantRelationshipProductListDataImportPlugin();
        $merchantRelationshipProductListDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function getDataImporterReaderConfigurationTransfer(string $filePath): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $filePath);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        return $dataImportConfigurationTransfer;
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Assert
        $merchantRelationshipProductListDataImportPlugin = new MerchantRelationshipProductListDataImportPlugin();
        $this->assertSame(MerchantRelationshipProductListDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP_PRODUCT_LIST, $merchantRelationshipProductListDataImportPlugin->getImportType());
    }
}
