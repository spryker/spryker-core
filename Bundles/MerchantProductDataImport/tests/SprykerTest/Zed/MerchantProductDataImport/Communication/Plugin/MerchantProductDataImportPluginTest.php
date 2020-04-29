<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\MerchantProductDataImportPlugin;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantProductDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductDataImportPluginTest extends Unit
{
    public const MERCHANT_REFERENCE = 'MERCHANT_TEST';
    public const PRODUCT_ABSTRACT_SKU = 'test_sku';

    /**
     * @var \SprykerTest\Zed\MerchantProductDataImport\MerchantProductDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantProductAbstractTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE,
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_SKU,
        ]);
        $t1 = SpyMerchantProductAbstractQuery::create()->find();
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant_product.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $merchantProductDataImportPlugin = new MerchantProductDataImportPlugin();
        $dataImporterReportTransfer = $merchantProductDataImportPlugin->import($dataImportConfigurationTransfer);
        $t2 = SpyMerchantProductAbstractQuery::create()->find();
        // Assert
        $this->assertEmpty($dataImporterReportTransfer->getDataImporterReports());
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertMerchantProductAbstractDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $merchantOpeningHoursDateScheduleDataImportPlugin = new MerchantProductDataImportPlugin();

        // Assert
        $this->assertSame(MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT, $merchantOpeningHoursDateScheduleDataImportPlugin->getImportType());
    }
}
