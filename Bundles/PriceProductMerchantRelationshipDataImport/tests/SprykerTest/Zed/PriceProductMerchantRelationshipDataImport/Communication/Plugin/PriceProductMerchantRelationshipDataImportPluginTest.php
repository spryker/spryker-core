<?php

namespace SprykerTest\Zed\PriceProductMerchantRelationshipDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Communication\Plugin\PriceProductMerchantRelationshipDataImportPlugin;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\PriceProductMerchantRelationshipDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceProductMerchantRelationshipDataImport
 * @group Communication
 * @group Plugin
 * @group PriceProductMerchantRelationshipDataImportPluginTest
 * Add your own group annotations below this line
 */
class PriceProductMerchantRelationshipDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductMerchantRelationshipDataImport\PriceProductMerchantRelationshipDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $PriceProductMerchantRelationshipDataImportPlugin = new PriceProductMerchantRelationshipDataImportPlugin();
        $this->assertSame(
            PriceProductMerchantRelationshipDataImportConfig::IMPORT_TYPE_PRICE_PRODUCT_MERCHANT_RELATIONSHIP,
            $PriceProductMerchantRelationshipDataImportPlugin->getImportType()
        );
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/price_product_merchant_relationship.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $dataImportConfigurationTransfer->setThrowException(true);

        $PriceProductMerchantRelationshipDataImportPlugin = new PriceProductMerchantRelationshipDataImportPlugin();
        $dataImporterReportTransfer = $PriceProductMerchantRelationshipDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }
}
